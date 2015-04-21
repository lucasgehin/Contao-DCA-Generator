<?php
namespace Controller;
class DcaController {
    public function display()
    {
        $loader = new \Twig_Loader_Filesystem('Template');
        $twig = new \Twig_Environment($loader, array('debug' => true));
        $tmpl = $twig->loadTemplate('dca.html.twig');
        $tmpl->display(array());
    }

    public function generate()
    {
$code = '
<?php
 
/**
 * Table '.$_POST['name'].'
 */

$GLOBALS[\'TL_DCA\'][\''.$_POST['name'].'\'] = array
(';
$keys = '';
for ($i=1; $i <= $_POST['nb_cles']; $i++) { 
    if (isset($_POST['cle_champ_'.$i]) && ($_POST['cle_champ_'.$i] != '')) {
        $keys .= '\''.$_POST['cle_champ_'.$i].' => \''.$_POST['cle_valeur_'.$i].'\',
                ';
    }
    
}
//config
$code .= '
    // Config
    \'config\' => array
    (
        \'dataContainer\'               => \''.$_POST['datacontainer'].'\',
        \'enableVersioning\'            => '.(isset($_POST['versionning']) ? 'true' : 'false').',
        \'switchToEdit\'                => '.(isset($_POST['switchtoedit']) ? 'true' : 'false').',
        \'closed\'                      => '.(isset($_POST['closed']) ? 'true' : 'false').',
        \'switchToEdit\'                => '.(isset($_POST['switchtoedit']) ? 'true' : 'false').',
        \'notEditable\'                 => '.(isset($_POST['noteditable']) ? 'true' : 'false').',
        \'notDeletable\'                => '.(isset($_POST['notdeletable']) ? 'true' : 'false').',
        '.(($_POST['ptable']) ? '\'ptable\'                      => \''.$_POST['ptable'].'\',' : '' ).'
        '.(($_POST['ctable']) ? '\'ctable\'                      => array(\''.$_POST['ptable'].'\'),' : '' ).'
        \'sql\' => array
        (
            \'keys\' => array
            (
                '.$keys.'
            )
        )
    ),
';


//list
$explode_sorting_fields = explode(',', $_POST['sorting_fields']);
$list_sorting_fields = "";
foreach ($explode_sorting_fields as $explode_sorting_field) {
    if ($explode_sorting_field != '')
        $list_sorting_fields .= '\''.$explode_sorting_field.'\',';
}
$list_sorting_fields = substr($list_sorting_fields,0,-1);

//label
$explode_label_fields = explode(',', $_POST['label_fields']);
$list_label_fields = "";
foreach ($explode_label_fields as $explode_label_field) {
    if ($explode_label_field != '')
        $list_label_fields .= '\''.$explode_label_field.'\',';
}
$list_label_fields = substr($list_label_fields,0,-1);

//global operation
$global_operation_all = '\'all\' => array
            (
                \'label\'               => &$GLOBALS[\'TL_LANG\'][\'MSC\'][\'all\'],
                \'href\'                => \'act=select\',
                \'class\'               => \'header_edit_all\',
                \'attributes\'          => \'onclick="Backend.getScrollOffset()" accesskey="e"\'
            ),';

//operations
$fr_buttons = '';
$fr_buttons .= '$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'new\']    = array(\'Nouveau\', \'Ajouter nouveau\');
';
$operation_edit = '\'edit\' => array
            (
                \'label\'               => &$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'edit\'],
                \'href\'                => \'act=edit\',
                \'icon\'                => \'edit.gif\'
            ),';
if (isset($_POST['operation_edit'])) $fr_buttons .= '$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'edit\']    = array(\'Modifier\', \'Modifier ID %s\');
';
$operation_copy = '\'copy\' => array
            (
                \'label\'               => &$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'copy\'],
                \'href\'                => \'act=copy\',
                \'icon\'                => \'copy.gif\'
            ),';
if (isset($_POST['operation_copy'])) $fr_buttons .= '$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'copy\']    = array(\'Copier\', \'Copier ID %s\');
';
$operation_delete = '\'delete\' => array
            (
                \'label\'               => &$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'delete\'],
                \'href\'                => \'act=delete\',
                \'icon\'                => \'delete.gif\',
                \'attributes\'          => \'onclick="if(!confirm(\'\\\' . $GLOBALS[\'TL_LANG\'][\'MSC\'][\'deleteConfirm\'] . \'\\\'))return false;Backend.getScrollOffset()"\'
            ),';
if (isset($_POST['operation_delete'])) $fr_buttons .= '$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'delete\']    = array(\'Supprimer\', \'Supprimer ID %s\');
';
$operation_show = '\'show\' => array
            (
                \'label\'               => &$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'show\'],
                \'href\'                => \'act=show\',
                \'icon\'                => \'show.gif\'
            ),';
if (isset($_POST['operation_show'])) $fr_buttons .= '$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\'show\']    = array(\'Détails\', \'Afficher les détails ID %s\');
';

$code .= '
    //List
    \'list\' => array
    (
        \'sorting\' => array
        (
            \'mode\'                    => '.$_POST['mode'].',
            \'flag\'                    => '.$_POST['flag'].',
            \'panelLayout\'             => \''.$_POST['panellayout'].'\',
            \'fields\'                  => array('.$list_sorting_fields.'),
        ),
        \'label\' => array
        (
            \'fields\'                  => array('.$list_label_fields.'),
            \'format\'                  => \''.$_POST['label_format'].'\',
        ),
        \'global_operations\' => array
        (
            '.(isset($_POST['globaloperation_all']) ? $global_operation_all : "").'
        ),
        \'operations\' => array
        (
            '.(isset($_POST['operation_edit']) ? $operation_edit : "").'
            '.(isset($_POST['operation_copy']) ? $operation_copy : "").'
            '.(isset($_POST['operation_delete']) ? $operation_delete : "").'
            '.(isset($_POST['operation_show']) ? $operation_show : "").'
        ),
    ),
';

//palettes
$content_palette = '';
$fr_legends = '';
foreach ($_POST['palette'] as $palette) {
    if ($palette['legend'] != '') {
        $fr_legends .= '$GLOBALS[\'TL_LANG\'][\''.$_POST['name'].'\'][\''.$palette['legend'].'\'] = \''.$palette['legend_name'].'\'';
        $content_palette .= '{'.$palette['legend'].'}';
        foreach ($palette['fields'] as $palette_field) {
            if ($palette_field['label'] != '')
                $content_palette .= ','.$palette_field['label'];
        }
        $content_palette .= ';';
    }
}
$code .= '
    //Palettes
    \'palettes\' => array
    (
        \'default\'                     => \''.$content_palette.'\',
    ),
';

//fields
$field_id = '\'id\' => array
        (
            \'sql\'                     => "int(10) unsigned NOT NULL auto_increment"
        ),';
$field_tstamp = '\'tstamp\' => array
        (
            \'sql\'                     => "int(10) unsigned NOT NULL default \'0\'"
        ),';

$fields = '';
$fields_file = file_get_contents('./Config/fields.json');
$array_fields = json_decode($fields_file, true);
$eval_fields = file_get_contents('./Config/eval.json');
$array_evals = json_decode($eval_fields, true);

if (isset($_POST['field'])) {
    foreach ($_POST['field'] as $fie) {
        $fields .= '\''.$fie['name'].'\' => array
        (
            ';
        foreach ($fie['options'] as $fopt) {
            if ($fopt['champ'] && $fopt['value']) {
                $champ = intval($fopt['champ']);
                $option_value = $fopt['value'];
                $value = $array_fields['fields'][$champ]['value'];
                switch ($value) {
                    case 'string':
                        $option_value = '\''.$fopt['value'].'\'';
                        break;
                    case 'select':
                        $option_value = '\''.$fopt['value'].'\'';
                        break;
                    case 'text':
                        $option_value = '"'.$fopt['value'].'"';
                        break;
                    
                    default:
                        break;
                }
                
                $fields .= '\''.$array_fields['fields'][$champ]['title'].'\' => '.$option_value.',
            ';
            }
            
        }
        //eval
        if ($fie['eval']['exist'] == 'true') {
            $eval = 'array(';
            foreach ($fie['eval'] as $eva) {
                if (isset($eva['champ']) && isset($eva['value']) && $eva['champ'] && $eva['value']) {
                    $champ = intval($eva['champ']);
                    $eval_value = $eva['value'];
                    $value_eva = $array_evals['fields'][$champ]['value'];
                    switch ($value_eva) {
                        case 'string':
                            $eval_value = '\''.$eva['value'].'\'';
                            break;
                        case 'select':
                            $eval_value = '\''.$eva['value'].'\'';
                            break;
                        case 'text':
                            $eval_value = '"'.$eva['value'].'"';
                            break;
                        
                        default:
                            break;
                    }
                    $eval .= ' \''.$array_evals['fields'][$champ]['title'].'\' => '.$eval_value.',';
                }
            }
            $eval = substr($eval,0,-1);
            $eval .= '),';
        }
        $fields .= '\'eval\'                    => '.$eval.'
        ),
        ';
    }
}

$code .= '
    //Fields
    \'fields\' => array
    (
        '.(isset($_POST['fields_id']) ? $field_id : "").'
        '.(isset($_POST['fields_tstamp']) ? $field_tstamp : "").'
        '.$fields.'
    ),
';

$code .= ');';


$fr = '
<?php

/**
 * Fields
 */

/**
 * Legends
 */
'.$fr_legends.'

/**
 * Buttons
 */
'.$fr_buttons.'
';
        $loader = new \Twig_Loader_Filesystem('Template');
        $twig = new \Twig_Environment($loader, array('debug' => true));
        $tmpl = $twig->loadTemplate('dca_generate.html.twig');
        $tmpl->display(array("dca" => $code, "fr" => $fr, 'name' => $_POST['name']));
    }
}