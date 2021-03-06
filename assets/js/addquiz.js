

var $collectionHolder;

// setup an "add a answer" link
var $addAnswerLink = $('<button type="button" class="add_answer_link btn btn-success btn-sm">Add question</button>');

var $newLinkLi = $('<li class="list-group-item"></li>').append($addAnswerLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of answers
    $collectionHolder = $('ul.answers');

    $collectionHolder.append($newLinkLi);
    addAnswerForm($collectionHolder, $newLinkLi);
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $(".form-check-input").attr('checked',true);

    $addAnswerLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new answer form (see next code block)
        addAnswerForm($collectionHolder, $newLinkLi);
    });
});



function addAnswerFormDeleteLink($answerFormLi) {
    var $removeFormA = $('<button type="button" class="btn btn-outline-danger btn-sm">Delete</button>');

    $answerFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $answerFormLi.remove();
    });
}





function addAnswerForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');
    prototype.class="form-control col-sm";
    console.log(prototype);
    // get the new index
    // var index = $collectionHolder.size();
    var index = $collectionHolder.data('index');
    console.log($collectionHolder.find(':input').length);

    if($collectionHolder.find(':input').length < 12) {
        //   window.alert(index);
        var newForm = prototype;
        // You need this only if you didn't set 'label' => false in your answers field in TaskType
        // Replace '__name__label__' in the prototype's HTML to
        // instead be a number based on how many items we have
        // newForm = newForm.replace(/__name__label__/g, index);

        // Replace '__name__' in the prototype'  HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a answer" link li
        var $newFormLi = $('<li class="list-group-item"></li>').append(newForm);
        $newLinkLi.before($newFormLi);
        var Checkboxes = $('.form-check-input');
        //var indexOfCheckbox = $('.form-check-input').index(this);
        $(".form-check-input").on('click',function () {
            for(var cell  of Checkboxes){

                console.log(cell);
                console.log(this);
                if(cell == this){
                    this.checked=true;
                }
                else{
                   cell.checked=false;
                }
                //$('.form-check-input').prop('checked',false);
                //console.log($('.form-check-input').prop('checked',false));
            }
        })
        addAnswerFormDeleteLink($newFormLi);
    }
}


