<?php
class ControllerExtensionModuleHero3d extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/hero3d');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_setting_module->addModule('hero3d', $this->request->post);
            } else {
                $this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect(
                $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit']     = $this->language->get('text_edit');
        $data['text_enabled']  = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name']       = $this->language->get('entry_name');
        $data['entry_status']     = $this->language->get('entry_status');
        $data['entry_title']      = $this->language->get('entry_title');
        $data['entry_subtitle']   = $this->language->get('entry_subtitle');
        $data['entry_cta_text']   = $this->language->get('entry_cta_text');
        $data['entry_cta_link']   = $this->language->get('entry_cta_link');
        $data['entry_model_url']  = $this->language->get('entry_model_url');
        $data['entry_bg_color']   = $this->language->get('entry_bg_color');
        $data['entry_alpha']      = $this->language->get('entry_alpha');
        $data['entry_auto_rotate']= $this->language->get('entry_auto_rotate');
        $data['entry_height']     = $this->language->get('entry_height');
        $data['entry_aspect']     = $this->language->get('entry_aspect');

        $data['help_model_url']  = $this->language->get('help_model_url');
        $data['help_bg_color']   = $this->language->get('help_bg_color');
        $data['help_alpha']      = $this->language->get('help_alpha');
        $data['help_auto_rotate']= $this->language->get('help_auto_rotate');
        $data['help_height']     = $this->language->get('help_height');
        $data['help_aspect']     = $this->language->get('help_aspect');

        $data['button_save']   = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        // Errors
        $data['error_warning']  = isset($this->error['warning']) ? $this->error['warning'] : '';
        $data['error_name']     = isset($this->error['name']) ? $this->error['name'] : '';
        $data['error_model_url']= isset($this->error['model_url']) ? $this->error['model_url'] : '';

        // Breadcrumbs
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/hero3d', 'user_token=' . $this->session->data['user_token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/hero3d', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . (int)$this->request->get['module_id'], true)
            );
        }

        // Action/Cancel
        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/hero3d', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/hero3d', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . (int)$this->request->get['module_id'], true);
        }

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        // Load existing
        $module_info = array();
        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
        }

        // Fields
        $fields = array('name','status','title','subtitle','cta_text','cta_link','model_url','bg_color','alpha','auto_rotate','height','aspect');

        foreach ($fields as $field) {
            if (isset($this->request->post[$field])) {
                $data[$field] = $this->request->post[$field];
            } elseif (!empty($module_info) && isset($module_info[$field])) {
                $data[$field] = $module_info[$field];
            } else {
                // Defaults
                switch ($field) {
                    case 'status':      $data[$field] = 1; break;
                    case 'title':       $data[$field] = 'Your Product, Reimagined in 3D'; break;
                    case 'subtitle':    $data[$field] = 'Spin, zoom, and explore right on the homepage.'; break;
                    case 'cta_text':    $data[$field] = 'Shop Now'; break;
                    case 'cta_link':    $data[$field] = 'index.php?route=product/category&path=20'; break;
                    case 'model_url':   $data[$field] = 'image/3d/hero.glb'; break;
                    case 'bg_color':    $data[$field] = '#000000'; break;
                    case 'alpha':       $data[$field] = 0; break;
                    case 'auto_rotate': $data[$field] = 1; break;
                    case 'height':      $data[$field] = 520; break;
                    case 'aspect':      $data[$field] = '16/10'; break;
                    default:            $data[$field] = '';
                }
            }
        }

        $data['user_token'] = $this->session->data['user_token'];

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/hero3d', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/hero3d')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!isset($this->request->post['name']) || utf8_strlen($this->request->post['name']) < 3 || utf8_strlen($this->request->post['name']) > 64) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (empty($this->request->post['model_url'])) {
            $this->error['model_url'] = $this->language->get('error_model_url');
        }

        return !$this->error;
    }
}
