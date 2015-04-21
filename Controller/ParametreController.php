<?php
namespace Controller;
class ParametreController {
    public function display()
    {
        $fields = file_get_contents('./Config/fields.json');
        $eval = file_get_contents('./Config/eval.json');
        $loader = new \Twig_Loader_Filesystem('Template');
        $twig = new \Twig_Environment($loader, array('debug' => true));
        $tmpl = $twig->loadTemplate('params.html.twig');
        $tmpl->display(array('fields' => $fields, 'eval' => $eval));
    }

    public function maj()
    {
        file_put_contents('./Config/fields.json', $_POST['fields']);
        file_put_contents('./Config/eval.json', $_POST['eval']);
        $loader = new \Twig_Loader_Filesystem('Template');
        $twig = new \Twig_Environment($loader, array('debug' => true));
        $tmpl = $twig->loadTemplate('params.html.twig');
        $tmpl->display(array('fields' => $_POST['fields'], 'eval' => $_POST['eval'], 'success' => 'Modifications effectuées avec succès.'));
    }

    public function resetConfig()
    {
        $fields = file_get_contents('./Config/fields.lock');
        $eval = file_get_contents('./Config/eval.lock');
        file_put_contents('./Config/fields.json', $fields);
        file_put_contents('./Config/eval.json', $eval);

        echo json_encode(array('resultat' => 'Paramètres réinitialisés'));
    }

    public function getFields()
    {
        $fields = file_get_contents('./Config/fields.json');
        $array_fields = json_decode($fields, true);
        $fields_parsed = array();
        $i = 0;
        foreach ($array_fields['fields'] as $field) {
            $fields_parsed[] = array( "id" => $i, "text" => $field['title']);

            $i++;
        }
        echo json_encode($fields_parsed);
    }

    public function getEvals()
    {
        $evals = file_get_contents('./Config/eval.json');
        $array_evals = json_decode($evals, true);
        $evals_parsed = array();
        $i = 0;
        foreach ($array_evals['fields'] as $field) {
            $evals_parsed[] = array( "id" => $i, "text" => $field['title']);

            $i++;
        }
        echo json_encode($evals_parsed);
    }

    public function getFieldValue()
    {
        $field = $_POST['field'];
        $id = $_POST['id'];
        $option_id = $_POST['option_id'];
        $fields = file_get_contents('./Config/fields.json');
        $array_fields = json_decode($fields, true);
        $html = array();
        $html['id'] = $id;
        $html['option_id'] = $option_id;
        $type = $array_fields['fields'][$field]['value'];
        switch ($type) {
            case 'string':
                $html['display'] = '<input type="text" class="form-control" name="field['.$id.'][options]['.$option_id.'][value]" placeholder="Valeur du champ (chaine de caractère, pas de \')" />';
                break;
            case 'boolean':
                $html['display'] = '<select name="field['.$id.'][options]['.$option_id.'][value]" class="form-control"><option value="true">true</option><option value="false">false</option></select>';
                break;
            case 'int':
                $html['display'] = '<input type="number" name="field['.$id.'][options]['.$option_id.'][value]" class="form-control" placeholder="Valeur du champ (nombre)"  />';
                break;
            case 'select':
                $html['display'] = '<select name="field['.$id.'][options]['.$option_id.'][value]" class="form-control">';
                $explode_option = explode(',', $array_fields['fields'][$field]['options']);
                foreach ($explode_option as $opt) {
                    $html['display'] .= '<option value="'.$opt.'">'.$opt.'</option>';
                }
                $html['display'] .= '</select>';
                break;
            case 'text':
                $html['display'] = '<input type="text" class="form-control" name="field['.$id.'][options]['.$option_id.'][value]" placeholder="Valeur du champ (texte)" />';
                break;
            case 'array':
                $html['display'] = '<input type="text" class="form-control" name="field['.$id.'][options]['.$option_id.'][value]" placeholder="Valeur du champ" value="array(\'index\' => \'value\')" />';
                break;
            
            default:
                $html['display'] = '<input name="field['.$id.'][options]['.$option_id.'][value]" type="text" class="form-control" disabled>';
                break;
        }

        echo json_encode($html);
    }

    public function getEvalValue()
    {
        $field = $_POST['field'];
        $id = $_POST['id'];
        $option_id = $_POST['option_id'];
        $evals = file_get_contents('./Config/eval.json');
        $array_evals = json_decode($evals, true);
        $html = array();
        $html['id'] = $id;
        $html['option_id'] = $option_id;
        $type = $array_evals['fields'][$field]['value'];
        switch ($type) {
            case 'string':
                $html['display'] = '<input type="text" class="form-control" name="field['.$id.'][eval]['.$option_id.'][value]" placeholder="Valeur du champ (chaine de caractère, pas de \')" />';
                break;
            case 'boolean':
                $html['display'] = '<select name="field['.$id.'][eval]['.$option_id.'][value]" class="form-control"><option value="true">true</option><option value="false">false</option></select>';
                break;
            case 'int':
                $html['display'] = '<input type="number" name="field['.$id.'][eval]['.$option_id.'][value]" class="form-control" placeholder="Valeur du champ (nombre)"  />';
                break;
            case 'text':
                $html['display'] = '<input type="text" class="form-control" name="field['.$id.'][eval]['.$option_id.'][value]" placeholder="Valeur du champ (texte)" />';
                break;
            case 'array':
                $html['display'] = '<input type="text" class="form-control" name="field['.$id.'][eval]['.$option_id.'][value]" placeholder="Valeur du champ" value="array(\'index\' => \'value\')" />';
                break;
            case 'select':
                $html['display'] = '<select name="field['.$id.'][eval]['.$option_id.'][value]" class="form-control">';
                $explode_option = explode(',', $array_evals['fields'][$field]['options']);
                foreach ($explode_option as $opt) {
                    $html['display'] .= '<option value="'.$opt.'">'.$opt.'</option>';
                }
                $html['display'] .= '</select>';
                break;
            default:
                $html['display'] = '<input name="field['.$id.'][eval]['.$option_id.'][value]" type="text" class="form-control" disabled>';
                break;
        }


        echo json_encode($html);
    }
}