<div class="columns">
    <p>
        Drag and drop photos into the area below to upload. After a successful upload, you can edit a photo to set the caption.
        <br>
        <a href="/photos">Click here to return to the photos page</a> when you are done.
    </p>
</div>

<form action="/admin/photos/upload"
      class="dropzone"
      id="photo-upload"></form>

<?= $this->Html->scriptBlock("

Dropzone.options.photoUpload = {
    maxFilesize: 5,
    acceptedFiles: '.jpg, .JPG, .png, .PNG',
    init: function() {
        this.on('success', function(file, response) {

            if (!response) {
                return alertify.error('Error uploading photo');
            }

            response = JSON.parse(response);
            if (!response.success) {
                console.log(response.errors);
            }

        });
        this.on('error', function(file, errorMessage) {
            alertify.error('Error: ' + errorMessage);
        });
    }
};

", ["block" => "scriptBottom"]) ?>