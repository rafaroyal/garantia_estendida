var ComponentsSelect2 = function() {

    var handleDemo = function() {

        // Set the "bootstrap" theme as the default theme for all Select2
        // widgets.
        //
        // @see https://github.com/select2/select2/issues/2927
        $.fn.select2.defaults.set("theme", "bootstrap");

        var placeholder = "Select a State";

        $(".select2, .select2-multiple").select2({
            placeholder: placeholder,
            width: null
        });

        $(".select2-allow-clear").select2({
            allowClear: true,
            placeholder: placeholder,
            width: null
        });

        
 // @see https://select2.github.io/examples.html#data-ajax
        function formatRepo(repo) {
            if (repo.loading) return repo.items;

            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__title'>" + repo.items.codigo + " - " + repo.items.text + "</div></div>";
            if (repo.items.sem_resultado) {
                markup = "<div class='select2-result-repository__description'>" + repo.items.sem_resultado + "</div>";
            }

            return markup;
        }

       /*function formatRepoSelection(repo) {
            return repo;
        }*/
        
$( ".js-data-procedimento-ajax" ).select2({   
    placeholder: "Buscar por procedimentos...",
    allowClear: true,
    width: "off",
    ajax: {
        url: "inc/lista_procedimentos.php",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term 
            };
        },
        processResults: function(data, page) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: data
                    };
                },
        cache: false
    },
    escapeMarkup: function(markup) {
        return markup;
    }, // let our custom formatter work
    minimumInputLength: 2,
    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
    templateResult: formatRepo
    //templateSelection: formatRepoSelection
});

    


    }

    return {
        //main function to initiate the module
        init: function() {
            handleDemo();
        }
    };

}();

if (App.isAngularJsApp() === false) {
    jQuery(document).ready(function() {
        ComponentsSelect2.init();
    });
}

$( document ).on( "click", "li.select2-results__option--highlighted", function() {
        //alert('ds');
        
    });
