<?php
class ControllerExtensionModuleHero3d extends Controller {
    public function index($setting) {
        // Language (optional)
        $this->load->language('extension/module/hero3d');

        // Styles
        $this->document->addStyle('catalog/view/stylesheet/hero3d.css');

        // Unique module instance id
        static $module = 0;
        $data['module_id'] = $module++;

        // Pass settings to view with sane defaults
        $data['title']       = isset($setting['title']) ? $setting['title'] : '';
        $data['subtitle']    = isset($setting['subtitle']) ? $setting['subtitle'] : '';
        $data['cta_text']    = isset($setting['cta_text']) ? $setting['cta_text'] : '';
        $data['cta_link']    = isset($setting['cta_link']) ? $setting['cta_link'] : '';
        $data['model_url']   = isset($setting['model_url']) ? $setting['model_url'] : 'image/3d/hero.glb';
        $data['bg_color']    = isset($setting['bg_color']) ? $setting['bg_color'] : '#000000';
        $data['alpha']       = !empty($setting['alpha']) ? 1 : 0;
        $data['auto_rotate'] = !empty($setting['auto_rotate']) ? 1 : 0;
        $data['height']      = isset($setting['height']) ? (int)$setting['height'] : 520;
        $data['aspect']      = isset($setting['aspect']) ? $setting['aspect'] : '16/10';

        return $this->load->view('extension/module/hero3d', $data);
    }
}
