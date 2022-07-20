// Fitur Pembersihan Kolom Input
$(".modal").on("hide.bs.modal", function (event) {
  $(event.target).find("form input").val("");
  $(event.target).find("form select").val("");
});

// Fitur Pembersihan class validasi dan Judul Modal
$(".modal").on("show.bs.modal", function (event) {
  $(event.target).find("form input").removeClass("is-invalid is-valid");
  $(event.target).find("form select").removeClass("is-invalid is-valid");
  $(event.target).find('form button[type="submit"]').addClass("disabled");

  let modalForm = $(event.target).find("form")[0];
  let modalId = $(event.target)[0].id;

  // Penyesuaian form uploadFile
  if (modalForm.classList.contains("upload")) {
    modalForm.id = modalForm.className;
    $('input[name="form_id"]').val(modalForm.id);
    $('input[name="parent"]').val(event.relatedTarget.dataset["parent"]);
    $('input[name="parent_id"]').val(event.relatedTarget.dataset["parent_id"]);
    $('input[name="type"]').val(event.relatedTarget.dataset["type"]);
  } else {
    $('input[name="form_id"]').val(modalForm.id);
  }

  // Penyesuaian form addJob
  if (modalForm.id == "addJob") {
    $('input[name="unit_id"]').val(event.relatedTarget.dataset["unitid"]);
  }

  // Injeksi value dari segmentid untuk form tambah unit
  $('input[name="segment_id"]').val(event.relatedTarget.dataset["segmentid"]);

  if (modalId == "modalTambahSegmen") {
    $(".modal-title").text("Tambah Segmen - Proyek A");
  } else if (modalId == "modalTambahUnit") {
    $(".modal-title").text(
      "Tambah Unit - " + event.relatedTarget.dataset["segmentname"]
    );
  } else if (modalId == "modalTambahPekerjaan") {
    $(".modal-title").text(
      "Tambah Pekerjaan - " + event.relatedTarget.dataset["unitname"]
    );
  } else if (modalId == "modalEditUnit") {
    $(".modal-title").text(
      "Edit Unit - " + event.relatedTarget.dataset["unitcode"]
    );
  } else if (modalId == "modalEditJob") {
    $(".modal-title").text(
      "Ubah Detail - " + event.relatedTarget.dataset["jobname"]
    );
  }
});

// Populasi input form edit
function populate(element, data) {
  let inputs = $(element.dataset["bsTarget"]).find("form input");
  let entries = Object.entries(data);

  for (const [key, value] of entries) {
    for (let i = 0; i < inputs.length; i++) {
      if (inputs[i].name == key) {
        inputs[i].value = value;
      }
    }
    if (key == "status") {
      $(element.dataset["bsTarget"]).find("form select").val(value);
    }
  }
}

// Edit Segmen
function populateSegment(element, data) {
  let segment_id = $(element).siblings("select").val();
  let inputs = $(element.dataset["bsTarget"]).find("form input");
  let select = $(element.dataset["bsTarget"]).find("form select")[0];

  data.forEach((segment) => {
    if (segment["segment_id"] == segment_id) {
      let entries = Object.entries(segment);

      for (const [key, value] of entries) {
        for (let i = 0; i < inputs.length; i++) {
          if (inputs[i].name == key) {
            inputs[i].value = value;
          } else if (select.name == key) {
            select.value = value;
          }
        }
      }
    }
  });
  // let inputs = $(element.dataset["bsTarget"]).find("form input");
  // let entries = Object.entries(data);

  // for (const [key, value] of entries) {
  //   for (let i = 0; i < inputs.length; i++) {
  //     if (inputs[i].name == key) {
  //       inputs[i].value = value;
  //     }
  //   }
  // }
}

// AJAX - Live Validation
(function () {
  "use strict";

  // Penonaktifan key ENTER
  $(document).keypress(function (event) {
    if (event.which == "13") {
      event.preventDefault();
    }
  });

  $(".live").on("change", function (event) {
    // let form = document.getElementById(event.target.form.id);
    let dataForm = $(event.target.form).serialize();

    let submitButton = $("form#" + event.target.form.id).find(
      'button[type="submit"]'
    );

    let element = $(this);
    let elementName = element[0].name;

    $.ajax({
      type: "POST",
      url: "http://localhost:8080/checkInput",
      data: dataForm,
      dataType: "JSON",
      success: function (data) {
        const errors = data.errors;
        if (errors == "") {
          submitButton.removeClass("disabled");
        } else {
          submitButton.addClass("disabled");
        }

        if (errors.hasOwnProperty(elementName)) {
          $(element).removeClass("is-valid");
          $(element).addClass("is-invalid");
          element.siblings(".invalid-feedback").html(errors[elementName]);
        } else if (!errors.hasOwnProperty(elementName)) {
          $(element).removeClass("is-invalid");
          $(element).addClass("is-valid");

          element.siblings(".invalid-feedback").html("");
        }
      },
    });
  });
})();

function deleteSegment() {
  let form = $(event.target.form)[0];
  let selectValue = $(form).siblings("select").val();
  $(form).attr("action", "http://localhost:8080/delete/segment/" + selectValue);

  if (confirm("Hapus Segmen?")) {
    $(form).submit();
  }
}

function enableOption() {
  if (event.target.value != "null") {
    $(".input-group button").attr("disabled", false);
  } else {
    $(".input-group button").attr("disabled", true);
  }
}

function enableProgress() {
  if (event.target.value == "Berjalan") {
    $('input[name="progress"]').attr("disabled", false);
  } else {
    $('input[name="progress"]').attr("disabled", true);
  }
}
