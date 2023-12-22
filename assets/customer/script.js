$(document).ready(() => {
  // login and signup form section
  $(".openForm").click(() => {
    document.cookie.includes("user_id")
      ? (location.href = "./profile.php")
      : $("#loginModal").css("display", "flex");
  });

  var showLogin = () => {
    $("#loginModal").css("display", "flex");
    $("#loginTitle").css("border-color", "white");
    $("#signupTitle").css("border-color", "#00804a");
    $(".loginFormBody").show();
    $(".signUpFormBody").hide();
    $("#loginSignUpForm").addClass("loginFormWidth");
    $("#loginSignUpForm").removeClass("signUpFormWidth");
  };

  var showSignUp = () => {
    $("#loginModal").css("display", "flex");
    $("#loginTitle").css("border-color", "#00804a");
    $("#signupTitle").css("border-color", "white");
    $(".loginFormBody").hide();
    $(".signUpFormBody").css("display", "flex");
    $("#loginSignUpForm").removeClass("loginFormWidth");
    $("#loginSignUpForm").addClass("signUpFormWidth");
  };

  $(".searchBar").on("input", (event) => {
    let searchTerm = $(event.target).val();
    if (searchTerm.length > 2) {
      $.ajax({
        url: "../server/customer/searchProduct.php",
        method: "post",
        data: { searchTerm: searchTerm },
        success: function (response) {
          $(".searchResult").html(response);
        },
        error: function (response) {
          console.log(response);
        },
      });
      // Fetch and display search results here
      $(".searchResult").show();
    } else {
      $(".searchResult").hide();
    }
  });

  $(".searchBar").on("click", "p", function () {
    var id = $(this).attr("id");
    if (id) {
      location.href = "./product.php?product_id=" + id;
    }
  });

  $(document).click((event) => {
    if (!$(event.target).closest(".searchBar").length) {
      $(".searchResult").hide();
    }
  });

  $("#loginTitle").click(() => showLogin());
  $("#signupTitle").click(() => showSignUp());
  location.search.includes("login") && showLogin();

  // header hiding section
  var prevScrollpos = $(window).scrollTop();
  $(window).scroll(() => {
    var value = prevScrollpos > $(window).scrollTop() ? "0" : "-200px";
    var value2 = prevScrollpos > $(window).scrollTop() ? "-200px" : "60px";
    $(".headerSec").css("top", value);
    $(".scrollToTopBtn").css("bottom", value2);
    prevScrollpos = $(window).scrollTop();
  });

  $(".logoCrop").click(() => (location.href = "./"));

  //product.php changing product quantity
  $(".plusMinQuantity").click(function () {
    if ($(this).text() === "+") {
      $(this)
        .prev()
        .val(function (i, oldval) {
          return ++oldval >= 999 ? 999 : oldval;
        });
    } else {
      $(this)
        .next()
        .val(function (i, oldval) {
          console.log(oldval);
          return --oldval <= 0 ? 1 : oldval;
        });
    }
  });

  //navigate to product
  $(".productCard").click(function () {
    var product_id = $(this).find(".productAddToCart").attr("id");
    location.href = "./product.php?product_id=" + product_id;
  });

  //nagivate to products list
  $(".headerCateogries").click(function () {
    location.href = "./products.php?cat=" + $(this).html();
  });

  //navigate to cart
  $("#shoppingCart").click(() => (location.href = "./cart.php"));

  //navigate to checkout
  $("#checkOut").click(() => (location.href = "./checkout.php"));

  // scroll to top function
  $(".scrollToTopBtn").click(() => window.scrollTo(0, 0));

  $(".profileMenu p").click(function () {
    $("#" + $(this).text()).prop("checked", true);
  });

  $(".addAddress").click(function () {
    var form = ".addAddressForm";
    clearForm(form);
    $(form).show();
    // Scroll to the bottom of the common container for both forms
    $("#profileAddress").scrollTop($(".addressBody")[0].scrollHeight);
  });

  $(".closeForm").click(() => {
    $(".addPaymentForm").hide();
    $(".addAddressForm").hide();
    $("#loginModal").hide();
  });

  $("#selectAllItem").click(function () {
    $(".selectedCartItem").prop("checked", $(this).prop("checked"));
  });
  $(".selectedCartItem").click(function () {
    $(".selectedCartItem:checked").length === $(".selectedCartItem").length &&
      $("#selectAllItem").prop("checked", true);
    !$(this).prop("checked") && $("#selectAllItem").prop("checked", false);
  });

  // loading function
  function startSpinning(selector) {
    $(selector).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
  }

  function stopSpinning(selector, innerHTML) {
    $(selector).html(innerHTML);
  }

  var addressExtraData;

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
          if (response.includes("login required")) {
            location.href = "../customer/index.php?login=1";
          }
          var success = response.includes("successful");
          floatAlert(success, response);
          if (typeof callback === "function" && success) {
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

  submitForm(
    "#renewPasswordEmailBtn",
    "#resetPasswordEmailForm",
    "../server/customer/sendTempPassword.php",
    () => {
      setTimeout(() => {
        location.reload();
      }, 2000);
    }
  );

  submitForm(
    "#passwordUpdateBtn",
    "#renewPasswordForm",
    "../server/customer/resetPassword.php",
    () => {
      setTimeout(() => {
        location.href = "./index.php?login=1";
      }, 2000);
    }
  );

  submitForm(
    "#contactUsBtn",
    ".contactUsForm",
    "../server/customer/contactUs.php"
  );

  submitForm(
    "#loginBtn",
    ".loginFormBody",
    "../server/customer/login.php",
    () => {
      setTimeout(() => {
        location.reload();
      }, 2000);
    }
  );

  submitForm(
    "#signUpBtn",
    ".signUpFormBody",
    "../server/customer/signup.php",
    () => {
      setTimeout(() => {
        clearForm(".signUpFormBody");
        showLogin();
      }, 2000);
    }
  );

  submitForm(
    "#addressSubmit",
    ".addAddressForm",
    "../server/customer/address.php",
    () => {
      clearForm(".addAddressForm");
      $(".addAddressForm").hide();
      location.href = "./profile.php?address=1";
    }
  );

  submitForm(
    "#productAddToCartSubmit",
    ".addToCartForm",
    "../server/customer/addToCart.php"
  );
  submitForm(
    "#profileFormUpdateBtn",
    ".profileForm",
    "../server/customer/profile.php"
  );
  submitForm(
    "#resetPasswordBtn",
    "#resetPasswordForm",
    "../server/customer/resetPassword.php"
  );

  $("#logoutBtn").click(() => {
    $.ajax({
      url: "../server/customer/logout.php",
      type: "POST",
      success: function (response) {
        var success = response.includes("successful");
        floatAlert(success, response);
        setTimeout(() => {
          location.href = "./index.php";
        }, 1500);
      },
      error: function (response) {
        console.log(response);
      },
    });
  });

  $(".addAddress").click(() => {
    $("#addressSubmitType").attr("name", "insertAddress");
  });
  $(".listOfAddress .editLink").click(function () {
    var address_id = $(this).closest(".listOfAddress").attr("id");
    var address_name = $(this)
      .closest(".listOfAddress")
      .find(".address_name")
      .text();
    var address = $(this).closest(".listOfAddress").find(".address").text();

    $(".addAddressForm").show();
    $(".addAddressForm #address_name").val(address_name);
    $(".addAddressForm #address").val(address);
    $("#address_id").val(address_id);
    $("#addressSubmitType").attr("name", "updateAddress");
    $("#profileAddress").scrollTop($(".addressBody")[0].scrollHeight);
  });

  $(".listOfAddress .deleteLink").click(function () {
    if (confirm("Are you sure you want to delete this address?")) {
      var address_id = $(this).closest(".listOfAddress").attr("id");
      $("#addressSubmitType").attr("name", "deleteAddress");
      const formData = new FormData();
      formData.append("address_id", address_id);
      formData.append("deleteAddress", 1);

      $.ajax({
        url: "../server/customer/address.php",
        data: formData,
        type: "POST",
        contentType: false,
        processData: false,
        success: function (response) {
          var success = response.includes("successful");
          floatAlert(success, response);
          success && $("#" + address_id).remove();
        },
        error: function (response) {
          console.log(response);
        },
      });
    }
  });

  $("#proceedPaymentBtn").click(function () {
    var oriHTML = $(this).html();
    startSpinning(this);

    var cartData = { subtotal: 0, productList: [] };

    cartData["payment_option_id"] = $(".selectedPaymentMethod:checked").val();
    cartData["address_id"] = $(".address").attr("id");

    $(".orderSummaryList").each(function () {
      var unitPrice = $(this).find(".cartItemPrice:first-child").text();
      unitPrice = parseFloat(unitPrice.slice(3));

      var quantity = $(this).find(".cartProductQuantity").text();
      quantity = parseInt(quantity.slice(2));

      var id = parseInt(this.id);

      productDetail = {
        product_id: id,
        quantity: quantity,
        unitPrice: unitPrice,
      };
      cartData["productList"].push(productDetail);
      cartData["subtotal"] += quantity * unitPrice;
    });

    $.ajax({
      url: "../server/customer/placeOrder.php",
      data: cartData,
      type: "POST",
      success: function (response) {
        if (response.includes("login required")) {
          location.href = "../customer/index.php?login=1";
        }
        var success = response.includes("successful");
        floatAlert(success, response);
        setTimeout(() => {
          location.href = "./index.php";
        }, 2000);
      },
      error: function (response) {
        console.log(response);
      },
    });
    stopSpinning(this, oriHTML);
  });

  $(".deleteCartItem").click(function () {
    if (confirm("Are you sure you want to delete this item?")) {
      var cartItemList = $(this).closest(".cartItemList");
      var selectedCartItemInput = cartItemList.find(".selectedCartItem");
      var selectedCartItemValue = selectedCartItemInput.val();
      $.ajax({
        url: "../server/customer/addToCart.php",
        method: "post",
        data: { product_id: selectedCartItemValue, cart_quantity: 0 },
        success: function (response) {
          var success = response.includes("successful");
          floatAlert(success, response);
          if ($(".cartItemList").length === 1) {
            location.reload();
          } else {
            cartItemList.remove();
          }
        },
        error: function (response) {
          console.log(response);
        },
      });
    }
  });
  function calculateTotal() {
    var total = 0;
    $(".cartSubTotal").each(function () {
      var cartItemList = $(this).closest(".cartItemList");
      var checked = cartItemList.find("input:checked").length > 0;
      var subtotal = parseFloat($(this).text());
      console.log(checked);

      if (checked) {
        total += subtotal;
      }
    });
    $("#totalprice").text(Number(total).toFixed(2));
    return;
  }

  $(".productDetailSec .plusMinQuantity").click(function () {
    var subtotal = Number(
      $("#productPrice").html() * $(".productQuantity").val()
    ).toFixed(2);

    $(".prodcutSubTotal").html(subtotal);
  });

  var timeout3s;

  $(".cartItemList .plusMinQuantity").click(function () {
    var cartItemList = $(this).closest(".cartItemList");
    var productInput = cartItemList.find(".selectedCartItem");
    var subtotal = cartItemList.find(".cartSubTotal");

    var cart_quantity = cartItemList.find(".cartProductQuantity").val();
    var unitPrice = cartItemList.find(".cartItemPrice").eq(0).text();
    unitPrice = parseFloat(unitPrice.slice(3));
    var product_id = productInput.val();

    subtotal.text(Number(cart_quantity * unitPrice).toFixed(2));
    calculateTotal();
    clearTimeout(timeout3s);

    timeout3s = setTimeout(() => {
      $.ajax({
        url: "../server/customer/addToCart.php",
        method: "post",
        data: { product_id: product_id, cart_quantity: cart_quantity },
        success: function (response) {
          var success = response.includes("successful");
          floatAlert(success, response);
        },
        error: function (response) {
          console.log(response);
        },
      });
    }, 3000);
  });

  $(".selectedCartItem, #selectAllItem").change(() => {
    calculateTotal();
  });

  function clearForm(selector) {
    $(selector + " input").val("");
  }

  //alert function
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

  //image slider function
  var imgCounter = 1;
  $(".imgSlider > img").click(function () {
    var indexOfImg = $(this).index() + 1;
    var selectedImg = ".imgSlider img:nth-child(" + indexOfImg + ")";
    var imgSrc = $(selectedImg).attr("src");
    $(".imgSlider *").removeClass("imgSelected");
    $(selectedImg).addClass("imgSelected");
    $("#mainProductImg").attr("src", imgSrc);
    imgCounter = indexOfImg;
  });
  $(".arrow").click(function () {
    // right = true, left = false
    if ($(this).hasClass("rightArrow")) {
      imgCounter =
        imgCounter < $(".imgSlider img").length ? ++imgCounter : imgCounter;
    } else {
      imgCounter = imgCounter > 1 ? --imgCounter : imgCounter;
    }
    var selectedImg = ".imgSlider img:nth-child(" + imgCounter + ")";
    $(".imgSlider *").removeClass("imgSelected");
    $(selectedImg).addClass("imgSelected");
    var imgSrc = $(selectedImg).attr("src");
    $("#mainProductImg").attr("src", imgSrc);
  });

  let currentIndex = -1;
  let sliderLength = $(".slider-image").length;
  var sliderTimeOut;
  if (sliderLength > 1) {
    for (var i = 0; i < sliderLength; i++) {
      $(".slideAuto").append($("<label></label>").addClass("slide-btn"));
    }
    changeSlide(1);

    $(".slider").on("click", ".slide-btn", function () {
      changeSlide($(this).index() - currentIndex);
    });

    $(".next").click(() => {
      changeSlide(1);
    });
    $(".prev").click(() => {
      changeSlide(-1);
    });

    function changeSlide(n) {
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

  //sort products
  $(".filter").on("change", function () {
    // Get the selected value of the filter
    var filterValue = $(this).val();

    // Get all the product cards
    var productCards = $(".productCard2");

    // Sort the product cards based on the selected value
    switch (filterValue) {
      case "PriceL2H":
        // Sort by price: Low to High
        productCards.sort(function (a, b) {
          var floatA = parseFloat($(a).find(".productPrice").html().slice(3));
          var floatB = parseFloat($(b).find(".productPrice").html().slice(3));
          return floatA - floatB;
        });
        break;
      case "PriceH2L":
        // Sort by price: High to Low
        productCards.sort(function (a, b) {
          var floatA = parseFloat($(a).find(".productPrice").html().slice(3));
          var floatB = parseFloat($(b).find(".productPrice").html().slice(3));
          return floatB - floatA;
        });
        break;
      default:
        // Sort by name
        productCards.sort(function (a, b) {
          var nameA = $(a).find(".productName").text();
          var nameB = $(b).find(".productName").text();
          return nameA.localeCompare(nameB);
        });
        break;
    }

    // Append the sorted product cards to the container
    $(".productCards2").html("");
    productCards.each(function () {
      $(".productCards2").append(this);
    });

    $(".productCard").click(function () {
      var product_id = $(this).find(".productAddToCart").attr("id");
      location.href = "./product.php?product_id=" + product_id;
    });
  });
});
