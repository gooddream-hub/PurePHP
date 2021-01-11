in_progress = false;

$( document ).ready(function() {
    bindTitle();
    bindSection();
    disableImgLinks();
    editImg();
    schedule_email();

    $('#picker').dateTimePicker();
        $('#picker-no-time').dateTimePicker({ showTime: false, dateFormat: 'DD/MM/YYYY'});
});

function saveTitle(){
	var updated = $('#updated').val();
	$('.current_edit').html(updated);
	$('.current_edit').removeClass('current_edit');
	$('#saveTitle').remove();
	in_progress = false;
}


function editTitle(el){
  if(in_progress == false){
  	  
  	  in_progress = true;
	  $(el).addClass('current_edit');
	  var html = $(el).html().trim();
	 
	  $(el).html('<textarea id="updated" style="font-size:12px;line-height:13px;" rows="3" cols="150">'+html+'</textarea>');
	  $(el).after('<button onclick="saveTitle();" id="saveTitle">Save</button>');
	}
}

function bindTitle(){
	$('.edit').click(function(){
		editTitle(this);
	})
}

function deleteSection(){
	$('.removeTable').remove();
}

function bindSection(){
	$('.section_edit').hover(function(){
		$(this).toggleClass('removeTable');
		$(this).prepend('<button style="margin:20px 0 20px 45%;" onclick="deleteSection();" class="deleteSection">Delete Section</button>');	
	},
	function(){
		$(this).toggleClass('removeTable');
		$('.deleteSection').remove();
		
	})
}

function disableImgLinks(){
	$('.imgLink').click(function() {
		return false;
    });
}

function schedule_email(){
	$('#schedule_blast').click(function(){
		//markup = $("html").html();
		markup = new XMLSerializer().serializeToString(document);
		date = $('#result').val();
		subject = $('#email_subject').val();
		if(subject == '') {
			$('.schedule_error').html('Please select subject');
		} else if(date == '') {
			$('.schedule_error').html('Please select schedule date');
		} else {
			$('.schedule_error').html('');
			rand = Math.random();

			$.post( "../../controller/marketing/schedule_email.php?"+rand, { markup: markup, date: date, subject: subject})
				.done(function( data ) {
					$('#scheduledModal').modal();
			});
		}
	})
}

function editImg(){
	$('.editImg').click(function(){
		console.log();
		$('#exampleModalCenter').modal();
	})
	$('#file-input').click(function(){
		upload();
	})
}

function upload(){
	let result = document.querySelector('.result'),
	img_result = document.querySelector('.img-result'),
	img_w = document.querySelector('.img-w'),
	img_h = document.querySelector('.img-h'),
	save = document.querySelector('.save'),
	cropped = document.querySelector('.cropped'),
	dwn = document.querySelector('.download'),
	upload = document.querySelector('#file-input'),
	cropper = '';

	console.log('upload called');
	upload.addEventListener('change', e => {
	  if (e.target.files.length) {
	    // start file reader
	    const reader = new FileReader();
	    reader.onload = e => {
	      if (e.target.result) {
	        // create new image
	        let img = document.createElement('img');
	        img.id = 'image';
	        img.src = e.target.result;
	        // clean result before
	        result.innerHTML = '';
	        // append new image
	        result.appendChild(img);
	        // show save btn and options
	        save.classList.remove('hide');
	        // init cropper
	        //cropper = new Cropper(img, {aspectRatio:415/320});
	        cropper = new Cropper(img);
	      }
	    };
	    reader.readAsDataURL(e.target.files[0]);
	  }
	});

	save.addEventListener('click', e => {
		e.preventDefault();
		// get result to data uri
		let imgSrc = cropper.getCroppedCanvas({
		width: 590 // input value
		}).toDataURL('image/jpeg');
		// remove hide class of img
		cropped.classList.remove('hide');
		img_result.classList.remove('hide');
		// show image cropped
		//cropped.src = imgSrc;
		//dwn.classList.remove('hide');
		//dwn.download = 'imagename.png';
		//dwn.setAttribute('href', imgSrc);
		$.ajax({
			type: "POST",
			url: "../../controller/marketing/newsletter.php?",
			data: { 
				imgBase64: imgSrc
			}
		}).done(function(response) {
			//return image file url and put in dom
			$('#editImg').attr("src",response);
			$('#exampleModalCenter').modal('toggle');
		});
	});
}
                  
