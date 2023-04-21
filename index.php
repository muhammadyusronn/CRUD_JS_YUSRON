<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap demo</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
      crossorigin="anonymous"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <h1 class="text-center">Tugas Advanced Teacher - Koding Next</h1>
        <!-- Button trigger modal -->
        <div class="col-md-2 mb-2">
          <button
          id="btnAdd"
          type="button"
          class="btn btn-primary btn-small"
          data-bs-toggle="modal"
          data-bs-target="#exampleModal"
          >
          Add New
          </button>
        </div>
        <div id="table-container"></div>
      </div>
      <!-- Modal -->
        <div
          class="modal fade"
          id="exampleModal"
          tabindex="-1"
          aria-labelledby="exampleModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                  Transaction Task
                </h1>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <form id="form-task">
                  <div class="mb-3">
                    <label for="task" class="form-label">Task</label>
                    <input type="hidden" class="form-control" id="id" name="id">
                    <input type="text" id="baseURL" value="<?= $actual_link =
                      (empty($_SERVER["HTTPS"]) ? "http" : "https") .
                      "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">
                    <input type="text" class="form-control" id="task" name="task">
                  </div>
                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description">
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
      <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"
      >
    </script>
    <script>
      $(document).ready(function () {
        getData()
      });
      $("#btnAdd").click(function(){
        resetForm()
      })
      $("#form-task").submit(function(event) {
        var ajaxRequest;
        // Prevent default posting of form - put here to work in case of errors
        event.preventDefault();

        // setup some local variables
        var $form = $(this);

        // Let's select and cache all the fields
        var $inputs = $form.find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = $form.serialize();

        // Let's disable the inputs for the duration of the Ajax request.
        // Note: we disable elements AFTER the form data has been serialized.
        // Disabled form elements will not be serialized.
        $inputs.prop("disabled", true);
        var urlAction = ""
        if($("#id").val()===""){
          urlAction=$("#baseURL").val()+"action/transaksi.php?act=insert"
        }else{
          urlAction=$("#baseURL").val()+"action/transaksi.php?act=update"
        }
        // Fire off the request to /form.php
        request = $.ajax({
            url: urlAction,
            type: "post",
            data: serializedData
        });

        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR){
            // Log a message to the console
             alert(response.message);
             if(response.status=="success"){
              getData()
              $("#exampleModal").modal('hide');
             }
             
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            // Log the error to the console
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
        });

        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // Reenable the inputs
            $inputs.prop("disabled", false);
        });

      })
      function editData(id){
        resetForm()
         $.ajax({    
              type: "GET",
              url: $("#baseURL").val()+"action/transaksi.php?act=edit", 
              data:{editId:id},            
              dataType: "html",                  
              success: function(data){   
                var jsonData=JSON.parse(data);  
                $("#id").val(jsonData.id);               
                $("#task").val(jsonData.task);               
                $("#description").val(jsonData.description);
                $("#exampleModal").modal('show');
              }
          });
      }
      function deleteData(id){
         $.ajax({    
            type: "GET",
            url: $("#baseURL").val()+"action/transaksi.php?act=delete",
            data:{deleteId:id},            
            dataType: "html",                  
            success: function(data){   
            alert(data);
            getData();
              
            }
        });
      }
      function getData(){
         $.ajax({
          type: "GET",
          url: $("#baseURL").val()+"action/transaksi.php?act=getAll",
          dataType: "html",
          success: function (data) {
            $("#table-container").html(data);
          },
        });
      }
      function resetForm(){
        $("#task").val("")
        $("#description").val("")
        $("#id").val("")
      }
    </script>
  </body>
</html>
