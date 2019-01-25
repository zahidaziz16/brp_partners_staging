<?php

class Theme_Admin_ExportController extends TB_AdminController
{
    /**
     * @var Theme_Admin_ExportModel
     */
    protected $exportModel;

    public function init()
    {
        ini_set('open_basedir', null);
        $this->exportModel = $this->getModel('export');
    }

    public function index()
    {
        $skins_exist = $this->engine->getDbSettingsHelper()->groupExists('skin_custom_' . $this->engine->getThemeId(), 0);
        $sliders_exist = $this->engine->getDbSettingsHelper()->groupExists('fire_slider', 0);
        $presets_exist = $this->engine->getDbSettingsHelper()->groupExists('preset', 0);

        $this->data['skins_exist']   = $skins_exist;
        $this->data['sliders_exist'] = $sliders_exist;
        $this->data['presets_exist'] = $presets_exist;

        $this->renderTemplate('theme_export');
    }

    public function export()
    {
        if (empty($this->request->post['export'])) {
            return $this->sendJsonError('Nothing to export.');
        }

        list($export, $images) = $this->doExport((array) $this->request->post['export']);

        $export = base64_encode(gzcompress(serialize($export), 9));

        $result = array(
            'export' => $export
        );

        if (extension_loaded('zip')) {

            $cache_dir = $this->context->getImageDir() . '/cache/tb';

            if (!is_dir($cache_dir)) {
                mkdir($cache_dir, 0777);
            }

            if (!is_writable($cache_dir)) {
                return $this->sendJsonError('Cannot write to ' . $cache_dir);
            }

            $filename = 'export_' . md5(session_id());
            $filepath = $cache_dir . '/' . $filename . '.zip';

            if (is_file($filepath)) {
                unlink($filepath);
            }

            $zip = new ZipArchive();

            $zip->open($filepath, ZipArchive::CREATE);

            foreach ($images as $src_image => $dest_image) {
                $zip->addFile($this->context->getImageDir() . '/' . $src_image, $dest_image);
            }

            $zip->addFromString('settings.dat', $export);

            $zip->close();

            $result['export_url'] = $this->context->getImageUrl() . 'cache/tb/' . $filename . '.zip';
        }

        $this->sendJsonSuccess('Export succeeded', $result);
    }

    protected function doExport($export_features)
    {
        $theme_settings = $this->engine->getThemeModel()->getSettings(true);

        if (empty($theme_settings)) {
            return $this->sendJsonError('There are no saved settings for the selected store');
        }

        $colors = $theme_settings['colors'];
        unset(
            $theme_settings['colors'],
            $theme_settings['store_id'],
            $theme_settings['install_info']
        );

        $export = $this->exportModel->generate($theme_settings, $export_features);

        $export['from_theme'] = $this->engine->getThemeInfo();
        $export['export_info'] = array(
            'ip'             => TB_Utils::getIp(),
            'host'           => $this->context->getHost(),
            'store_url'      => $this->context->getStoreUrl(),
            'date'           => date('d.m.Y H:i'),
            'engine_version' => $this->engine->getVersion(),
            'oc_version'     => VERSION,
            'languages'      => $this->engine->getEnabledLanguages(false)
        );

        if (in_array('colors', $export_features)) {
            $export['colors'] = $colors;
        }

        $images = array();
        if (in_array('images', $export_features)) {
            $images = $this->exportModel->extractExportImages($export);
        }

        if (!empty($images)) {
            $export['images'] = array();

            foreach ($images as $key => $image) {
                list(, $exported_image) = explode('/', $image, 2) + array( 1 => NULL);
                $exported_image = 'images/' . $exported_image;

                $export['images'][]  = $exported_image;
            }

            $images = array_combine(array_values($images), array_values($export['images']));
        }

        return array($export, $images);
    }
}