<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Ace Theme Editor
 * 
 * @package AceThemeEditor 
 * @author 荒野無燈
 * @version 1.0.0
 * @link http://nanodm.net
 */
class AceThemeEditor_Plugin implements Typecho_Plugin_Interface
{
    //get theme list from https://github.com/ajaxorg/ace/tree/master/lib/ace/theme
    //cat /tmp/theme.txt| grep ".js" | awk '{print $1}' | sed "s/.js//g" | awk '{printf "\x27%s\x27 => \x27%s\x27,\n", $1,$1}'
    /** ace theme */
    static $themes = [
        'ambiance' => 'ambiance',
        'chaos' => 'chaos',
        'chrome' => 'chrome',
        'clouds' => 'clouds',
        'clouds_midnight' => 'clouds_midnight',
        'cobalt' => 'cobalt',
        'crimson_editor' => 'crimson_editor',
        'dawn' => 'dawn',
        'dracula' => 'dracula',
        'dreamweaver' => 'dreamweaver',
        'eclipse' => 'eclipse',
        'github' => 'github',
        'gob' => 'gob',
        'gruvbox' => 'gruvbox',
        'idle_fingers' => 'idle_fingers',
        'iplastic' => 'iplastic',
        'katzenmilch' => 'katzenmilch',
        'kr_theme' => 'kr_theme',
        'kuroir' => 'kuroir',
        'merbivore' => 'merbivore',
        'merbivore_soft' => 'merbivore_soft',
        'mono_industrial' => 'mono_industrial',
        'monokai' => 'monokai',
        'pastel_on_dark' => 'pastel_on_dark',
        'solarized_dark' => 'solarized_dark',
        'solarized_light' => 'solarized_light',
        'sqlserver' => 'sqlserver',
        'terminal' => 'terminal',
        'textmate' => 'textmate',
        'tomorrow' => 'tomorrow',
        'tomorrow_night' => 'tomorrow_night',
        'tomorrow_night_blue' => 'tomorrow_night_blue',
        'tomorrow_night_bright' => 'tomorrow_night_bright',
        'tomorrow_night_eighties' => 'tomorrow_night_eighties',
        'twilight' => 'twilight',
        'vibrant_ink' => 'vibrant_ink',
        'xcode' => 'xcode',        
    ];

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('admin/theme-editor.php')->bottom = array('AceThemeEditor_Plugin', 'render');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {

        $theme = new Typecho_Widget_Helper_Form_Element_Select('theme', self::$themes, 'tomorrow', _t('主题'));
        $form->addInput($theme);
    }

     /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
        $theme = Helper::options()->plugin('AceThemeEditor')->theme;
        $theme = trim($theme);
        if (!in_array($theme, self::$themes) || $theme == '') {
            $theme = 'tomorrow';
        }
        echo <<<'EOT'
<!-- begin Ace Theme Editor by 荒野無燈 -->
<script src="https://cdn.bootcss.com/ace/1.4.4/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
(function ($){
    $(function() {
        let textarea = $('#content').hide();
        textarea.after('<div id="ace" style="font-size: .92857em;height: 560px;width: 100%;"></div>');

        let editor = ace.edit("ace");
        //see https://github.com/ajaxorg/ace/wiki/Configuring-Ace

EOT;

        printf("\n\t\teditor.setTheme('ace/theme/%s');\n\n", $theme);
        echo <<<'EOT'
        if (/file=.+\.php/.test(location.search) || location.search === "") {
            editor.session.setMode("ace/mode/php");
        } else if (/file=.+\.css/.test(location.search)) {
            editor.session.setMode("ace/mode/css");
        }      
        
        editor.resize(true);
        // editor.session.setUseSoftTabs(true);
        // editor.setOptions({
        //     autoScrollEditorIntoView: true,
        //     minLines: 30,
        //     maxLines: 800
        // });
        // editor.getSession().setNewLineMode('unix');

        editor.getSession().setValue(textarea.val());

        editor.getSession().on('change', function(){
            textarea.val(editor.getSession().getValue());
        });
    });
})(jQuery);
</script>
<!-- end Ace Theme Editor by 荒野無燈 -->

EOT;
    }
}
