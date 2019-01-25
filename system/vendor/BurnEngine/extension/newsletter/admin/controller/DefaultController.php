<?php

class Newsletter_Admin_DefaultController extends TB_AdminController
{
    /**
     * @var Newsletter_Admin_DefaultModel
     */
    protected $defaultModel;

    public function init()
    {
        $this->defaultModel = $this->getModel('default');
    }

    public function index()
    {
        $this->data['newsletter'] = $this->defaultModel->getSettings();

        $this->renderTemplate('index');
    }

    public function subscribers()
    {
        $page = $this->getArrayKey('page', $this->request->get, 1);
        $pagination_limit = $this->getArrayKey('pagination_limit', $this->request->get, 100);

        $filter_data = array(
            'start'         => ($page - 1) * $pagination_limit,
            'limit'         => $pagination_limit,
            'search_string' => $this->getArrayKey('search_string', $this->request->get, '')
        );

        $records = $this->defaultModel->getSubscribers($filter_data);

        $this->data['subscribers'] = $records;
        $this->data['total_subscribers'] = $this->defaultModel->getSubscribers($filter_data, true);

        $url_data = TB_FormHelper::initFlatVarsSimple(array(
            'search_string' => null,
            'page'          => $page
        ), $this->request->get);

        $request_url = $this->buildUrl($url_data, array('search_string'));

        $translation = $this->engine->loadOcTranslation();

        $pagination = new Pagination();
        $pagination->total = $this->data['total_subscribers'];
        $pagination->page  = $page;
        $pagination->limit = $pagination_limit;
        $pagination->text  = $translation['text_pagination'];
        $pagination->url   = $this->tbUrl->generate('default/subscribers', 'page={page}&' . $request_url);

        $this->data = array_merge($this->data, $url_data);
        $this->data['pagination'] = $pagination->render();
        $this->data['filter_request_url'] = $this->tbUrl->generate('default/editor', $request_url);

        $this->data['settings'] = $this->defaultModel->getSettings();

        $this->renderTemplate('subscribers');
    }

    public function exportSubscribers()
    {
        $settings = $this->defaultModel->getSettings();

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=NewsletterExport.csv");
        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
        header("Pragma: no-cache"); // HTTP 1.0
        header("Expires: 0"); // Proxies

        $output = fopen('php://output', 'w');

        foreach ($this->defaultModel->getSubscribers() as $row) {
            $export = array($row['email']);

            if ($settings['show_name']) {
                array_unshift($export, $row['name']);
            }

            fputcsv($output, array($row['email']));
        }

        fclose($output);
    }

    public function deleteSubscribers()
    {
        if ($this->request->server['REQUEST_METHOD'] != 'POST' || !$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        if (empty($this->request->post['selected_subscribers'])) {
            return $this->sendJsonError('There are no selected subscribers');
        }

        $this->defaultModel->deleteSubscribers((array) $this->request->post['selected_subscribers']);

        return $this->sendJsonSuccess('The subscribers have been deleted');
    }

    public function saveSettings()
    {
        if ($this->request->server['REQUEST_METHOD'] != 'POST' || !$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        if (empty($this->request->post['newsletter'])) {
            return $this->sendJsonError('Empty data!');
        }

        $this->defaultModel->saveSettings($this->request->post['newsletter']);

        return $this->sendJsonSuccess('The slider has been saved');
    }

    public function checkPermissions()
    {
        if (!$this->validate()) {
            return $this->sendJsonError('You do not have sufficient permissions to modify this module!');
        }

        return $this->sendJsonSuccess('Success');
    }
}