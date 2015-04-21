$(document).ready(function($) {
    $('#dca').addClass('active');

    
    $('.fa-question-circle').each(function() { 
            var tooltiptitle = $(this).attr("title");
            $(this).tooltip({'title': tooltiptitle});
        });


    $('#dca_form').submit(function(event) {
        
    });

    /*$.ajax({
        url: 'api/fields',
        type: 'GET',
    })
    .done(function (fields) {
        $(".select-fields").select2({
            placeholder: "Sélectionner un champ",
            data: fields
        })
    });*/

    
    
});

var currentCountCle;
function add_cle (value) {

    currentCountCle = $('#group-cles > div:last-child').data('id');
    if (currentCountCle != null) {
        currentCountCle = parseInt(currentCountCle);
        currentCountCle++;
    } else {
        currentCountCle = 1;
    }
    var template = $('#template_cles').html();
    template = template.replace(/__index__/g, currentCountCle);
    if (value) {
        template = template.replace(/__value__/g, $("#cle_champ_" + value).val());
    } else {
        template = template.replace(/__value__/g, "");
    }
    $('#group-cles').append(template);
    $('#nb_cles').attr('value', currentCountCle);
}

function remove_cle (index) {
    $("#group-cles").find("[data-id='" + index + "']").remove();
}

var currentCountLegend;
function add_palette_legend () {
    currentCountLegend = $('#group-legends > div:last-child').data('id');
    if (currentCountLegend != null) {
        currentCountLegend = parseInt(currentCountLegend);
        currentCountLegend++;
    } else {
        currentCountLegend = 1;
    }
    var template = $('#template_legend').html();
    template = template.replace(/__index__/g, currentCountLegend);
    $('#group-legends').append(template);
}

var currentCountLegendChamp;
function add_palette_champ (legend) {
    currentCountLegendChamp = $('#group-legends-' + legend + '-champs > div:last-child').data('id');
    if (currentCountLegendChamp != null) {
        currentCountLegendChamp = parseInt(currentCountLegendChamp);
        currentCountLegendChamp++;
    } else {
        currentCountLegendChamp = 1;
    }
    var template = $('#template_legend_champ').html();
    template = template.replace(/__index__/g, currentCountLegendChamp);
    template = template.replace(/__legend__/g, legend);
    $('#group-legends-' + legend + '-champs').append(template);

}

function remove_palette_champ (legend, index) {
    $('#group-legends-' + legend + '-champs').find("[data-id='" + index + "']").remove();
}

var currentCountField;
function add_field () {
    currentCountField = $('#group-fields > div:last-child').data('id');
    if (currentCountField != null) {
        currentCountField = parseInt(currentCountField);
        currentCountField++;
    } else {
        currentCountField = 0;
    }
    var template = $('#template_fields').html();
    template = template.replace(/__index__/g, currentCountField);
    $('#group-fields').append(template);
    $.ajax({
        url: 'api/fields',
        type: 'GET',
    })
    .done(function (fields) {
        $("#select-fields-"+currentCountField+"-options-0").select2({
            placeholder: "Sélectionner un champ",
            data: fields
        }).change(function(event) {
            $('#value-fields-'+$(this).parent().parent().parent().parent().parent().parent().parent().data('id')+'-options-0').html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: 'api/field',
                type: 'POST',
                data : {field : $(this).val(), id : $(this).parent().parent().parent().parent().parent().parent().parent().data('id'), option_id : 0}
            })
            .done(function (res) {
                $('#value-fields-'+res.id+'-options-0').html(res.display);
            });
            
        });
    });
    
}

var currentCountFieldOption;
function add_field_option (field) {
    currentCountFieldOption = $('#group-fields-'+field+'-option > div:last-child').data('id');
    if (currentCountFieldOption != null) {
        currentCountFieldOption = parseInt(currentCountFieldOption);
        currentCountFieldOption++;
    } else {
        currentCountFieldOption = 0;
    }
    var template = $('#template_fields_option').html();
    template = template.replace(/__index__/g, currentCountFieldOption);
    template = template.replace(/__field__/g, field);
    //load select 2
    
    $('#group-fields-'+field+'-option').append(template);
    $.ajax({
        url: 'api/fields',
        type: 'GET',
    })
    .done(function (fields) {
        $("#select-fields-"+field+"-options-"+currentCountFieldOption).select2({
            placeholder: "Sélectionner un champ",
            data: fields
        }).change(function(event) {
            $('#value-fields-'+field+'-options-'+$(this).data('id')).html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: 'api/field',
                type: 'POST',
                data : {field : $(this).val(), id : field, option_id: $(this).data('id')}
            })
            .done(function (res) {
                $('#value-fields-'+res.id+'-options-'+res.option_id).html(res.display);
            });
            
        });
    });
}

function remove_field_option (field, index) {
    $('#group-fields-'+field+'-option').find("[data-id='" + index + "']").remove();
}

function add_eval (field) {
    $('#add_eval_'+field).css('display', 'none');
    $('#remove_eval_'+field).css('display', '');
    $('#eval_'+field).css('display', '');
    $('#hidden_eval_'+field).attr('value', 'true');
    $.ajax({
        url: 'api/evals',
        type: 'GET',
    })
    .done(function (fields) {
        $("#select-fields-"+field+"-eval-0").select2({
            placeholder: "Sélectionner un champ",
            data: fields
        }).change(function(event) {
            $('#value-fields-'+field+'-eval-0').html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: 'api/eval',
                type: 'POST',
                data : {field : $(this).val(), id : field, option_id: $(this).data('id')}
            })
            .done(function (res) {
                $('#value-fields-'+res.id+'-eval-'+res.option_id).html(res.display);
            });
        });
    });
}

var currentCountFieldEval;
function add_eval_option (field) {
    currentCountFieldEval = $('#eval_'+field+' > div:last-child').data('id');
    if (currentCountFieldEval != null) {
        currentCountFieldEval = parseInt(currentCountFieldEval);
        currentCountFieldEval++;
    } else {
        currentCountFieldEval = 0;
    }
    var template = $('#template_eval_option').html();
    template = template.replace(/__index__/g, currentCountFieldEval);
    template = template.replace(/__field__/g, field);
    $('#eval_'+field).append(template);
    //load select 2
    $.ajax({
        url: 'api/evals',
        type: 'GET',
    })
    .done(function (fields) {
        $("#select-fields-"+field+"-eval-"+currentCountFieldEval).select2({
            placeholder: "Sélectionner un champ",
            data: fields
        }).change(function(event) {
            $('#value-fields-'+field+'-eval-'+$(this).data('id')).html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
                url: 'api/eval',
                type: 'POST',
                data : {field : $(this).val(), id : field, option_id: $(this).data('id')}
            })
            .done(function (res) {
                $('#value-fields-'+res.id+'-eval-'+res.option_id).html(res.display);
            });
        });
    });
}

function remove_eval (field) {
    $('#add_eval_'+field).css('display', '');
    $('#remove_eval_'+field).css('display', 'none');
    $('#eval_'+field).css('display', 'none');
    $('#hidden_eval_'+field).attr('value', 'false');
}

function remove_eval_option (field, index) {
    $('#eval_'+field).find("[data-id='" + index + "']").remove();
}