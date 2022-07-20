function substitute() {
  let fragment = `
    <button class="btn btn-sm btn-primary px-5 mx-2" type="submit">Save Changes</button>
    <button class="btn btn-sm btn-primary px-5 mx-2" type="reset" onclick="cancel()">Cancel</button>
    `;
  let inputs = $("#formedit").find("input");

  $("#buttoned").html(fragment);

  for (const input of inputs) {
    $(input).removeAttr("readonly");

    if ($(input).hasClass("form-control-plaintext")) {
      $(input).removeClass("form-control-plaintext");
      $(input).addClass("form-control");
    }

    if ($(input).attr("disabled")) {
      $(input).removeAttr("disabled");
      $(input).addClass("forDisable");
    }
  }
}

function cancel() {
  // let fragment = `<button class="btn btn-sm btn-primary px-5" type="button" onclick="substitute()">Edit</button>`;
  let inputs = $("#formedit").find("input");
  console.log(inputs);
  // $("#buttoned").html(fragment);

  for (const input of inputs) {
    // $(input).attr("readonly", true);
    $(input).val(input.defaultValue);

    // if ($(input).hasClass("form-control")) {
    //   $(input).removeClass("form-control");
    //   $(input).addClass("form-control-plaintext");
    // }

    // if ($(input).hasClass("forDisable")) {
    //   $(input).attr("disabled", true);
    // }
  }
}

function previous(object) {
  let inputs = $("#formedit").find("input");
  let select = $("#formedit").find("select")[0];
  let options = $("#formedit select option");

  for (const option of options) {
    $(option).attr("selected", false);

    if (option.value == object.groupid) {
      $(select).val(option.value).change();
    }
    console.log(option);
  }

  for (const input of inputs) {
    if (object.hasOwnProperty(input.name)) {
      $(input).val(object[input.name]);
    }

    if ($(input).hasClass("is-invalid")) {
      $(input).removeClass("is-invalid");
    }
  }
}
