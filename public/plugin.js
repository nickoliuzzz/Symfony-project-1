var arrayOfData = [];
var table = $('#here_table');
var kek =1;
var neededInSort =  Number(-1);
var temppage = 0;
var maxPage = -1;
var stringFromSearched = "";
var goToPage = 0;
var checkBoxes = [];
checkBoxes.push(-1);


appendData();
ajax();










function ajax() {
    $.ajax({
        url:"/ajax",
        method:"POST",
        data:{"arrayOfData":arrayOfData},
        dataType:"json",
        async:true,
        success:function (data) {
            var left = $("<button id='keks'>Left</button>\n");
            var searched = $("<input type='text' name='searched' id='searched'>");
            var right = $("<button id='keks1'>Right</button>\n");


            searched.keyup(function(e){
                goToPage = 0;
                temppage = 0;
                stringFromSearched = $('#searched').val();
                appendData();
                ajax();
            });
            left.click(function(e){
                goToPage = -1;
                appendData();
                ajax();
            });
            right.click(function(e){
                goToPage = 1;
                appendData();
                ajax();
            });


            table.children().remove();
            takeArray(data[1]);
            searched.val(stringFromSearched);
            table.append(searched);


            if(temppage > 0) {
                table.append(left);
            }
            if(temppage < maxPage) {
                table.append(right);
            }





            table.append("<table>");
            table.append("<tr>" );

            for(var cell of data[0]){
                table.append();
                $button = $("<td type='button' class='cells'>" + cell + "</td>");
                $button.on('click', sortButton);
                table.append($button);
            }
            table.append("</tr>" );
            data.shift();
            data.shift();
            for(var sem of data) {
                table.append("<tr>" );

                for (var elemen of sem) {
                    var content = decodeURI(elemen["content"]).replace(/"/g, '');
                    table.append("<td>" + content + "</dt>" );
                }
                var index = data[data.indexOf(sem)][0]['content'];
                $checkBox = $( "<input type='checkbox'  class='ans'/>");
           //     $checkBox.val(index);
                table.append($checkBox);
                console.log(checkBoxes);
                if(checkBoxes.indexOf(sem[0]["content"]) != -1)
                {
                    $checkBox.attr('checked', true);
                }
                $checkBox.on('click',function () {
                    var indexOfCheckbox = data[$('.ans').index(this)][0]["content"];
                    if(checkBoxes.indexOf(indexOfCheckbox) == -1)
                    {
                         checkBoxes.push(indexOfCheckbox);
                    }
                    else {
                        checkBoxes.splice(checkBoxes.indexOf(indexOfCheckbox), 1);
                    }
                });

                table.append("</tr>" );
            }
            table.append("</table>");
            $('#searched').focus();

        },
        error: function (response) {
        }
    });
}

function appendData() {
    arrayOfData = [];
    arrayOfData.push(neededInSort);
    arrayOfData.push(temppage);
    arrayOfData.push(goToPage);
    arrayOfData.push(maxPage);
    arrayOfData.push(stringFromSearched);
    arrayOfData.push(checkBoxes);
   // console.log(arrayOfData);
}

function takeArray(temp) {
    console.log(checkBoxes);
    neededInSort =temp[0];
    temppage = temp[1];
    goToPage = temp[2];
    maxPage = temp[3];
    stringFromSearched = temp[4];
    checkBoxes = temp[5].content;
    //console.log(temp[5]);
}

function sortButton(e){
    goToPage = 0;
    temppage = 0;
    var neededInSort1 = $('.cells').index(this) + 1;
    if(neededInSort1 == neededInSort) {
        neededInSort = -neededInSort;
    }
    else neededInSort = neededInSort1;
    appendData();
    ajax();
}