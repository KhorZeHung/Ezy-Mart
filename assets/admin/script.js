$(document).ready(() => {
  var pathway = window.location.pathname.split("/");
  var pages = pathway[pathway.length - 1].slice(0, -4);
  $("#" + pages + " > div:first-child").addClass("selected");

  //floating alert notification
  const floatAlert = function (successFail, message) {
    $("#floatAlert").css("bottom", "-200px");
    $("#floatAlert").empty();

    $("#floatAlert").append(
      successFail
        ? '<i class="fa fa-solid fa-circle-check success"></i>'
        : '<i class="fa-solid fa-circle-xmark"></i>'
    );
    $("#floatAlert").append("<p>" + message + "</p>");
    $("#floatAlert i").addClass(successFail ? "success" : "failed");
    $("#floatAlert").css("bottom", "30px");

    setTimeout(function () {
      $("#floatAlert").css("bottom", "-200px");
      setTimeout(() => {
        $("#floatAlert").empty();
      }, 500);
    }, 3000);
  };
  const closeNav = () => {
    $(".navBarTitle > p").hide();
    $(".navBarSelectionList").removeClass("navBarExtend");
  };

  // navigation bar effect
  $(".navBarSelectionList").click(function () {
    $(this).toggleClass("navBarExtend");
    $(this).hasClass("navBarExtend")
      ? setTimeout(() => {
          $(".navBarTitle > p").show();
        }, 200)
      : closeNav();
  });

  $(document).on("click", function (e) {
    var navList = $(".navBarSelectionList");
    !navList.is(e.target) &&
      !navList.has(e.target).length &&
      navList.hasClass("navBarExtend") &&
      closeNav();
  });

  // icon seleected effect
  $(".navBarSelection").click(function () {
    if (this.id !== "logout") {
      var url = "./" + this.id + ".php";
      $(".navBarSelectionList").hasClass("navBarExtend") &&
        window.location.replace(url);
    } else {
      $.ajax({
        url: "../server/admin/logout.php",
        success: function (response) {
          if (response.includes("successful")) {
            floatAlert(true, response);
            setTimeout(() => {
              location.href = "./login.php";
            }, 2000);
          } else {
            floatAlert(false, response);
          }
        },
        error: function (response) {
          console.log(response);
        },
      });
    }
  });

  showCustomFileUpload();
  function showCustomFileUpload() {
    if ($(".productImg").length >= 3) {
      $(".addPhoto").hide();
    } else {
      $(".addPhoto").show();
    }
  }

  // scroll to top
  var prevScrollpos = $(window).scrollTop();
  $(window).scroll(() => {
    $(".scrollToTopBtn").css(
      "bottom",
      prevScrollpos > $(window).scrollTop() ? "-200px" : "60px"
    );
    prevScrollpos = $(window).scrollTop();
  });

  $(".scrollToTopBtn").click(() => window.scrollTo(0, 0));

  // back to master page function
  $("#backToOrderBtn").click(() =>
    navigation.canGoBack ? history.back() : window.location.replace("./")
  );

  //dragging img to save sequence
  var updatePacket = {};

  $(".productImgSec, .contentSliderImgSec").on(
    "dragstart",
    ".productImg, .contentSliderImg",
    function () {
      $(this).addClass("dragging");
    }
  );
  $(".productImgSec, .contentSliderImgSec").on(
    "dragover",
    ".productImg, .contentSliderImg",
    function (e) {
      e.preventDefault();
      $(".updateBtn").removeAttr("disabled");
      const draggable = $(".dragging");
      const afterElement = getDragAfterElement(draggable, e.clientX);

      if (afterElement) {
        draggable.insertAfter(afterElement);
      } else {
        draggable.parent().prepend(draggable);
      }
    }
  );
  $(".productImgSec, .contentSliderImgSec").on(
    "dragend",
    ".productImg, .contentSliderImg",
    function () {
      $(this).removeClass("dragging");
      updatePreview($(this).parent());
    }
  );

  $("#updateContentSliderBtn").click(function (e) {
    e.preventDefault();

    var selector = this;
    var oriHTML = $(this).html();
    startSpinning(selector);

    const imgSrc = new Array();

    $(".contentSliderImg").each(function () {
      var srcArray = $(this).find("img").attr("src").split("/");
      imgSrc.push(srcArray[srcArray.length - 1]);
    });

    var formData = new FormData();

    formData.append("updateContentSlider", 1);
    formData.append("imgSrc", imgSrc);

    $.ajax({
      url: "../server/admin/slider_content.php",
      method: "POST",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        stopSpinning(selector, oriHTML);
        if (response.includes("successful")) {
          floatAlert(true, response);
        } else {
          floatAlert(false, response);
        }
      },
      error: function (xhr, status, error) {
        console.log(JSON.parse(error));
      },
    });
  });
  $("#updateProductDetailBtn").click(function (e) {
    e.preventDefault();

    var selector = this;
    var oriHTML = $(this).html();
    startSpinning(selector);

    const imgSrc = new Array();

    $(".productImg").each(function () {
      var srcArray = $(this).find("img").attr("src").split("/");
      imgSrc.push(srcArray[srcArray.length - 1]);
    });

    var formData = new FormData();
    formData.append("updateProduct", 1);
    formData.append("imgSrc", imgSrc);
    formData.append("pname", $("#pname").val());
    formData.append("product_id", $("#product_id").val());
    formData.append("price", $("#price").val());
    formData.append("category", $("#productCat").val());
    formData.append("pdetail", $("#pdetail").val());

    $.ajax({
      url: "../server/admin/product.php",
      method: "POST",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        console.log(response);
        stopSpinning(selector, oriHTML);
        response = JSON.parse(response);
        floatAlert(response.success, response.detail);
        if (oriHTML == "add") {
          location.href = "./productList.php";
        }
      },
      error: function (xhr, status, error) {
        console.log(JSON.parse(error));
      },
    });
  });

  function getDragAfterElement(container, x) {
    const draggableElements = [...$(container).siblings("div:not(.dragging)")];
    return draggableElements.reduce(
      (closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = x - box.left - box.width / 2;
        if (offset > 0 && offset < closest.offset) {
          return { offset: offset, element: child };
        } else {
          return closest;
        }
      },
      { offset: Number.POSITIVE_INFINITY }
    ).element;
  }

  let fiveSecTimeout;
  $("#sliderContentFiles").change(function (e) {
    e.preventDefault();
    var formData = new FormData();
    for (const file of this.files) {
      formData.append("sliderContentFiles[]", file);
    }

    formData.append("addNewFile", 1);

    $.ajax({
      url: "../server/admin/slider_content.php",
      type: "POST",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      async: true,
      success: function (response) {
        clearTimeout(fiveSecTimeout);
        parseText = JSON.parse(response);
        parseText.forEach(function (text) {
          var html = $("<p></p>").text(text["msg"]);

          if (text["msg"].includes("successful")) {
            html.addClass("successUpload");
            var imgDiv = $("<div></div>")
              .addClass("contentSliderImg center")
              .attr("id", text["id"]);
            var closeSpan = $("<span></span>")
              .addClass("material-symbols-outlined delete")
              .html("cancel");
            var img = $("<img>").attr("src", text["location"].slice(3));

            imgDiv.append(closeSpan).append(img);

            imgDiv.insertAfter($(".contentSliderImg").last());

            $(".slider").append(
              $(
                '<img src="' +
                  text["location"].slice(3) +
                  '" alt="Image" class="slider-image">'
              )
            );
            updatePreview($(".contentSliderImgSec"));
            slideAuto();
          } else {
            html.addClass("failUpload");
          }
          $(".uploadStatus").append(html);
        });
        fiveSecTimeout = setTimeout(() => {
          $(".uploadStatus").empty();
        }, 5000);
      },
      error: function (xhr, status, error) {
        console.log(JSON.parse(error));
      },
    });
  });

  $("#productImgFiles").change(function (e) {
    e.preventDefault();
    var formData = new FormData();
    for (const file of this.files) {
      formData.append("productImgFiles[]", file);
    }

    formData.append("addNewFile", $(".productImg").length);
    formData.append("product_id", $("#product_id").val());
    $.ajax({
      url: "../server/admin/product.php",
      type: "POST",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      async: true,
      success: function (response) {
        clearTimeout(fiveSecTimeout);

        console.log(response);
        parseText = JSON.parse(response);

        !$("#product_id").val() && $("#product_id").val(parseText[0]["id"]);

        parseText.forEach(function (text) {
          var html = $("<p></p>").text(text["msg"]);
          if (text["msg"].includes("successful")) {
            html.addClass("successUpload");

            var imgDiv = $("<div></div>")
              .addClass("productImg center")
              .attr("id", text["id"]);
            var closeSpan = $("<span></span>")
              .addClass("material-symbols-outlined delete")
              .html("cancel");
            var img = $("<img>").attr(
              "src",
              "../assets/image/" + text["location"]
            );
            imgDiv.append(closeSpan).append(img);
            imgDiv.insertBefore($("#productImgInputField"));
            showCustomFileUpload();
          } else {
            html.addClass("failUpload");
          }
          $(".uploadStatus").append(html);
        });
        fiveSecTimeout = setTimeout(() => {
          $(".uploadStatus").empty();
        }, 5000);
      },
      error: function (xhr, status, error) {
        console.log(JSON.parse(error));
      },
    });
  });

  $(".contentSliderImgSec").on("click", ".delete", function (e) {
    e.preventDefault();
    var data = { deletePhoto: 1 };
    data["imgUrl"] = $(this).next().attr("src");
    $parentID = $(this).parent().attr("id");
    if (confirm("Do you want to delete this photo?")) {
      $.ajax({
        url: "../server/admin/slider_content.php",
        type: "POST",
        data: data,
        success: function (response) {
          var html = $("<p></p>").text(response);

          if (response.includes("successful")) {
            html.addClass("successUpload");
            $("#" + $parentID).remove();
            updatePreview($(".contentSliderImgSec"));
            slideAuto();
          } else {
            html.addClass("failureUpload");
          }

          $(".uploadStatus").append(html);
          clearTimeout(fiveSecTimeout);
          fiveSecTimeout = setTimeout(() => {
            $(".uploadStatus").empty();
          }, 5000);
        },
        error: function (xhr, status, error) {
          console.log(JSON.parse(error));
        },
      });
    }
  });
  $(".productImgSec").on("click", ".delete", function (e) {
    e.preventDefault();
    const imgSrc = new Array();

    $(".productImg").each(function () {
      var srcArray = $(this).find("img").attr("src").split("/");
      imgSrc.push(srcArray[srcArray.length - 1]);
    });

    var formData = new FormData();
    parent = $(this).parent();
    parentID = parent.attr("id");
    formData.append("deletePhoto", parentID);
    formData.append("imgSrc", imgSrc);
    formData.append("sequence", parent.index());
    if (confirm("Do you want to delete this photo?")) {
      $.ajax({
        url: "../server/admin/product.php",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        async: true,
        success: function (response) {
          response = JSON.parse(response);
          floatAlert(response.success, response.detail);
          response.success && parent.remove();

          showCustomFileUpload();
        },
        error: function (xhr, status, error) {
          console.log(JSON.parse(error));
        },
      });
    }
  });
  $(".searchBar").on("keyup", "input.contactSearchBar", function () {
    var value = $(this).val();
    var input = $(this);
    if (value.length > 1) {
      var url = "../server/admin/contact_search.php";
      $.ajax({
        url: url,
        method: "post",
        data: { searchTerm: value },
        success: function (response) {
          response = JSON.parse(response);
          if (!response.success) {
            floatAlert(response.success, response.detail);
          } else {
            var results = response.detail;
            $(".searchResult").empty();
            if (results.length > 0) {
              results.forEach((result) => {
                var [returnhtml, returnid] = [
                  result.contact_name +
                    ", " +
                    result.contact_subject +
                    ", " +
                    result.contact_id,
                  result.contact_id,
                ];
                var resultBar = $("<p></p>")
                  .attr("id", returnid)
                  .html(returnhtml);
                $(".searchResult").append(resultBar);
              });
            } else {
              $(".searchResult").append($("<p></p>").html("no result"));
            }
          }
        },
        error: function (response) {
          console.log(response);
        },
      });
    } else {
      $(".searchResult").empty();
    }
  });
  $(".searchBar").on("keyup", "input.orderSearchBar", function () {
    var value = $(this).val();
    var input = $(this);
    if (value.length > 0) {
      var url = "../server/admin/order_search.php";
      $.ajax({
        url: url,
        method: "post",
        data: { searchTerm: value },
        success: function (response) {
          response = JSON.parse(response);
          if (!response.success) {
            floatAlert(response.success, response.detail);
          } else {
            var results = response.detail;
            $(".searchResult").empty();
            if (results.length > 0) {
              results.forEach((result) => {
                var [returnhtml, returnid] = [
                  result["order_id"] +
                    ", " +
                    result["user_name"] +
                    ", " +
                    result["address"],
                  result["order_id"],
                ];
                var resultBar = $("<p></p>")
                  .attr("id", returnid)
                  .html(returnhtml);
                $(".searchResult").append(resultBar);
              });
            } else {
              $(".searchResult").append($("<p></p>").html("no result"));
            }
          }
        },
        error: function (response) {
          console.log(response);
        },
      });
    } else {
      $(".searchResult").empty();
    }
  });
  $(".searchBar").on("keyup", "input.productSearchBar", function () {
    var value = $(this).val();
    var input = $(this);
    if (value.length > 1) {
      var url = "../server/admin/product_search.php";
      $.ajax({
        url: url,
        method: "post",
        data: { searchTerm: value },
        success: function (response) {
          response = JSON.parse(response);

          if (!response.success) {
            floatAlert(response.success, response.detail);
          } else {
            var results = response.detail;
            $(".searchResult").empty();
            if (results.length > 0) {
              results.forEach((result) => {
                var [returnhtml, returnid] = [
                  result["product_id"] + ", " + result["product_name"],
                  result["product_id"],
                ];
                var resultBar = $("<p></p>")
                  .attr("id", returnid)
                  .html(returnhtml);
                $(".searchResult").append(resultBar);
              });
            } else {
              $(".searchResult").append($("<p></p>").html("no result"));
            }
          }
        },
        error: function (response) {
          console.log(response);
        },
      });
    } else {
      $(".searchResult").empty();
    }
  });

  $("#statusOfOrder").on("change", function () {
    const val = $(this).val();
    const table = $("#acceptedOrderTable");
    const rows = table.find("tr").slice(1);
    rows.each(function () {
      const cell = $(this).find("td:eq(4)").text();
      if (cell == val || val === "*") {
        $(this).show();
      } else {
        $(this).hide();
      }
    });

    var odd = 0;
    $("#acceptedOrderTable tbody tr").each(function () {
      if ($(this).css("display") !== "none") {
        if (odd) {
          $(this).css("background-color", "rgb(198, 198, 198)");
          odd++;
        } else {
          $(this).css("background-color", "white");
          odd--;
        }
      }
    });
  });
  $("#productCatFilter").on("change", function () {
    const val = $(this).val();
    const table = $("#productTable");
    const rows = table.find("tr").slice(1);
    rows.each(function () {
      const cell = $(this).find("td:eq(3)").text();
      if (cell == val || val === "*") {
        $(this).show();
      } else {
        $(this).hide();
      }
    });

    var odd = 0;
    $("#productTable tbody tr").each(function () {
      if ($(this).css("display") !== "none") {
        if (odd) {
          $(this).css("background-color", "rgb(198, 198, 198)");
          odd++;
        } else {
          $(this).css("background-color", "white");
          odd--;
        }
      }
    });
  });

  $(".contactTable tr").dblclick(function () {
    location.href = "./message.php?contact_id=" + this.id;
  });
  $(".orderTable tr").dblclick(function () {
    location.href = "./order.php?order_id=" + this.id;
  });
  $("#productTable tr").dblclick(function () {
    location.href = "./product.php?product_id=" + this.id;
  });

  $("#contactSearchResult").on("click", "p", function () {
    location.href = "./message.php?contact_id=" + this.id;
  });
  $("#orderSearchResult").on("click", "p", function () {
    location.href = "./order.php?order_id=" + this.id;
  });
  $("#productSearchResult").on("click", "p", function () {
    location.href = "./product.php?product_id=" + this.id;
  });
  $(document).click(function (e) {
    if (!$(e.target).closest(".searchResult").length) {
      $(".searchBar input").val("");
      if ($(".searchResult").find("p").length >= 1) {
        $(".searchResult").hide();
      }
    }
  });

  // preview section
  let currentIndex = -1;
  var sliderTimeOut;
  slideAuto();
  changeSlide(1);

  $(".slider").on("click", ".slide-btn", function () {
    changeSlide($(this).index() - currentIndex);
  });

  $(".next").click(() => changeSlide(1));
  $(".prev").click(() => changeSlide(-1));

  function slideAuto() {
    $(".slideAuto").empty();
    var lengthOfImg = $(".slider").children("img").length;
    if (lengthOfImg == 1) {
      $(".next, .prev").hide();
    } else {
      $(".next, .prev").show();
      for (var i = 0; i < lengthOfImg; i++) {
        $(".slideAuto").append($("<label></label>").addClass("slide-btn"));
      }
    }
  }
  function changeSlide(n) {
    var sliderLength = $(".slider").children("img").length;
    if (sliderLength != 1) {
      $(".slider").children("img").hide();
      $(".slide-btn").removeClass("onShow");
      currentIndex += +n;
      if (currentIndex >= sliderLength) {
        currentIndex = 0;
      } else if (currentIndex < 0) {
        currentIndex = sliderLength - 1;
      }
      $(".slider").children("img").eq(currentIndex).fadeIn();
      $(".slide-btn").siblings().eq(currentIndex).addClass("onShow");
      clearTimeout(sliderTimeOut);
      sliderTimeOut = setTimeout(() => changeSlide(1), 5000);
    }
  }

  function updatePreview(selector) {
    $(".slider").find("img").remove();
    $(selector)
      .children("div")
      .map(function () {
        var imgFullSrc = $(this).children("img").attr("src");
        $(".slider").append(
          $("<img>").addClass("slider-image").attr("src", imgFullSrc)
        );
      });
    slideAuto();
  }

  function startSpinning(selector) {
    $(selector).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
  }

  function stopSpinning(selector, innerHTML) {
    $(selector).html(innerHTML);
  }

  function submitForm(selectorID, formClass, url, callback) {
    $(selectorID).click(function () {
      var selector = this;
      var formData = $(formClass).serialize();
      var oriHTML = $(this).html();
      startSpinning(selector);
      $.ajax({
        url: url,
        data: formData,
        type: "POST",
        success: function (response) {
          stopSpinning(selector, oriHTML);
          response = JSON.parse(response);
          if (response.detail.includes("login required")) {
            location.href = "../../admin/login.php";
          }
          floatAlert(response.success, response.detail);
          if (typeof callback === "function" && response.success) {
            callback();
          }
        },
        error: function (response) {
          stopSpinning(selector, oriHTML);
          console.log(response);
        },
      });
    });
  }

  submitForm("#loginBtn", ".loginForm", "../server/admin/login.php", () => {
    setTimeout(() => {
      location.href = "contactUs.php";
    }, 2000);
  });
  submitForm(
    "#updateOrderStatusBtn",
    ".orderStatusForm",
    "../server/admin/order.php",
    () => {
      setTimeout(() => {
        location.href = "orderList.php";
      }, 2000);
    }
  );

  submitForm("#replyBtn", ".messageForm", "../server/admin/reply.php");

  $(".activate").click(function () {
    const activate = $(this).hasClass("deactivate") ? "deactivate" : "activate";
    const product_id = $(this).attr("value");

    if (confirm("Are you sure you want to " + activate + " this product?")) {
      $.ajax({
        url: "../server/admin/product.php",
        method: "post",
        data: { activate: activate, product_id: product_id },
        success: function (response) {
          response = JSON.parse(response);
          floatAlert(response.success, response.detail);
          setTimeout(() => {
            location.reload();
          }, 2000);
        },
        error: function (response) {
          console.log(response);
        },
      });
    }
  });
});
