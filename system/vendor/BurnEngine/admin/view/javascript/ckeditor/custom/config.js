/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here.
  // For the complete reference:
  // http://docs.ckeditor.com/#!/api/CKEDITOR.config

  // The toolbar groups arrangement, optimized for two toolbar rows.
  config.toolbar = [
          ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
          ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'],
          ['Link', 'Unlink'],
          ['Image', 'Table'],
          '/',
          ['Styles', 'Format', 'FontSize'],
          ['JustifyLeft', 'JustifyCenter', 'JustifyRight'],
          ['HorizontalRule', 'SpecialChar'],
          ['Source'],
          ['Maximize']
  ];

  config.stylesSet = [
    { name: 'Blockquote left', element: 'blockquote', attributes: { 'class': 'left' } },
    { name: 'Blockquote right', element: 'blockquote', attributes: { 'class': 'right' } },
    { name: 'Custom Table', element: 'table', attributes: { 'class': 'tb_table_1' } },
    { name: 'Image left', element: 'img', attributes: { 'class': 'left' } },
    { name: 'Image right', element: 'img', attributes: { 'class': 'right' } },
    { name: 'Image center', element: 'img', attributes: { 'class': 'center clear' } },
    { name: 'Unordered list 1', element: 'ul', attributes: { 'class': 'tb_list_1' } },
    { name: 'Tiny button', element: 'a', attributes: { 'class': 'btn btn-xs' } },
    { name: 'Small button', element: 'a', attributes: { 'class': 'btn btn-sm' } },
    { name: 'Medium button', element: 'a', attributes: { 'class': 'btn' } },
    { name: 'Large button', element: 'a', attributes: { 'class': 'btn btn-lg' } },
    { name: 'Huge button', element: 'a', attributes: { 'class': 'btn btn-xl' } }
  ];

  // Remove some buttons, provided by the standard plugins, which we don't
  // need to have in the Standard(s) toolbar.
  //config.removeButtons = 'Underline,Subscript,Superscript';

  // Se the most common block elements.
  config.format_tags = 'p;h1;h2;h3;h4;pre';

  // Make dialogs simpler.
  //config.removeDialogTabs = 'image:advanced;link:advanced';
  
  config.fillEmptyBlocks     = false;
  config.allowedContent      = true;
  config.basicEntities       = false;

  // Captioned image classes

  config.image2_alignClasses = ['pull-left', 'center-block', 'pull-right'];

  CKEDITOR.dtd.$removeEmpty['i']    = false;
  CKEDITOR.dtd.$removeEmpty['span'] = false;

  CKEDITOR.on('instanceReady', function (ev) {
      ev.editor.dataProcessor.writer.sortAttributes = false;

      // Ends self closing tags the HTML4 way, like <br>.
      ev.editor.dataProcessor.htmlFilter.addRules({
          elements:
          {
              $: function (element) {
                  // Output dimensions of images as width and height
                  if (element.name == 'img') {
                      var style = element.attributes.style;

                      if (style) {
                          // Get the width from the style.
                          var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                              width = match && match[1];

                          // Get the height from the style.
                          match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                          var height = match && match[1];

                          if (width) {
                              element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                              element.attributes.width = width;
                          }

                          if (height) {
                              element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                              element.attributes.height = height;
                          }
                      }
                  }



                  if (!element.attributes.style)
                      delete element.attributes.style;

                  return element;
              }
          }
      });
  });

};
