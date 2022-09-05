<?php
// require autoload.php;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Capture and Upload Demo</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://use.fontawesome.com/1a667aa7a4.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function () {
            $("i").click(function () {
                // $("input[type='file']").trigger('click');
                $('#myModal').modal();
            });

            $(".capture").click(function(){
                $("input[id='capture-input']").trigger('click');
            });
        

            var gallery = document.querySelector('.gallery');
            var galleryItems = document.querySelectorAll('.gallery-item');
            var numOfItems = gallery.children.length;
            var itemWidth = 23; // percent: as set in css

            var featured = document.querySelector('.featured-item');

            var leftBtn = document.querySelector('.move-btn.left');
            var rightBtn = document.querySelector('.move-btn.right');
            var leftInterval;
            var rightInterval;

            var scrollRate = 0.2;
            var left;

            function selectItem(e) {
                if (e.target.classList.contains('active')) return;

                featured.style.backgroundImage = e.target.style.backgroundImage;

                for (var i = 0; i < galleryItems.length; i++) {
                    if (galleryItems[i].classList.contains('active'))
                        galleryItems[i].classList.remove('active');
                }

                e.target.classList.add('active');
            }

            function galleryWrapLeft() {
                var first = gallery.children[0];
                gallery.removeChild(first);
                gallery.style.left = -itemWidth + '%';
                gallery.appendChild(first);
                gallery.style.left = '0%';
            }

            function galleryWrapRight() {
                var last = gallery.children[gallery.children.length - 1];
                gallery.removeChild(last);
                gallery.insertBefore(last, gallery.children[0]);
                gallery.style.left = '-23%';
            }

            function moveLeft() {
                left = left || 0;

                leftInterval = setInterval(function () {
                    gallery.style.left = left + '%';

                    if (left > -itemWidth) {
                        left -= scrollRate;
                    } else {
                        left = 0;
                        galleryWrapLeft();
                    }
                }, 1);
            }

            function moveRight() {
                //Make sure there is element to the leftd
                if (left > -itemWidth && left < 0) {
                    left = left - itemWidth;

                    var last = gallery.children[gallery.children.length - 1];
                    gallery.removeChild(last);
                    gallery.style.left = left + '%';
                    gallery.insertBefore(last, gallery.children[0]);
                }

                left = left || 0;

                leftInterval = setInterval(function () {
                    gallery.style.left = left + '%';

                    if (left < 0) {
                        left += scrollRate;
                    } else {
                        left = -itemWidth;
                        galleryWrapRight();
                    }
                }, 1);
            }

            function stopMovement() {
                clearInterval(leftInterval);
                clearInterval(rightInterval);
            }

            // leftBtn.addEventListener('click', moveLeft);
            // leftBtn.addEventListener('click', stopMovement);
            // rightBtn.addEventListener('click', moveRight);
            // rightBtn.addEventListener('click', stopMovement);


            //Start this baby up
            (function init() {
                var images = [
                    'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/car.jpg',
                    'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/city.jpg',
                    'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/deer.jpg',
                    'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/flowers.jpg',
                    'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/food.jpg',
                    'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/landscape.jpg',
                    'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/night.jpg',
                    'https://s3-us-west-2.amazonaws.com/forconcepting/800Wide50Quality/table.jpg'
                ];

                //Set Initial Featured Image
                featured.style.backgroundImage = 'url(' + images[0] + ')';

                //Set Images for Gallery and Add Event Listeners
                for (var i = 0; i < galleryItems.length; i++) {
                    galleryItems[i].style.backgroundImage = 'url(' + images[i] + ')';
                    galleryItems[i].addEventListener('click', selectItem);
                }
            })();
        });


        // $('input[type="file"]').on('change', function() {
        //     var val = $(this).val();
        // })
    </script>
    <style>
        .category-box {
            width: 100%;
        }

        .category-form {
            width: inherit;
            background: #CFDFFD;
            height: 100%;

        }

        /* Useful Classes */
        .xy-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .transition {
            transition: all 350ms ease-in-out;
        }

        .r-3-2 {
            width: 100%;
            padding-bottom: 66.667%;
            background-color: #ddd;
        }

        .image-holder {
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }

        /* Main Styles */
        .gallery-wrapper {
            position: relative;
            overflow-x: scroll;
        }

        .gallery {
            position: relative;
            white-space: nowrap;
            font-size: 0;
        }

        .item-wrapper {
            cursor: pointer;
            width: 23%;
            /* arbitrary value */
            display: inline-block;
            background-color: white;
        }

        .gallery-item {
            opacity: 0.5;
        }

        .gallery-item.active {
            opacity: 1;
        }

        .controls {
            /* font-size: 0; */
            border-top: none;
        }

        .move-btn {
            display: inline-block;
            width: 48%;
            border: none;
            color: #ccc;
            background-color: transparent;
            padding: 0.2em 1.5em;
        }

        .move-btn:first-child {
            border-right: none;
        }

        .move-btn.left {
            cursor: w-resize;
        }

        .move-btn.right {
            cursor: e-resize;
        }
    </style>
</head>

<body>
    <div class="container category-box rounded m-3 mx-auto">
        <h2>Interior</h2>
        <div class="container-fluid category-form rounded p-2 mx-auto">
            <h3>Kitchen</h3>
            </br>
            <div class="container category-form-inputs p-3">
                <div class="row">
                    <div class="col-6">
                        <h4>Items</h4>
                    </div>
                    <div class="col-3 text-center">
                        <h4>Upload</h4>
                    </div>
                    <div class="col-3 text-center">
                        <h4>Count</h4>
                    </div>

                </div>
                <hr />


                <div class="row">
                    <div class="col-6">
                        <h5>Semi-Custom</h5>
                    </div>
                    <div class="col-3 text-center">
                        <i class="fa fa-camera" style="color:blue; font-size:150%;"></i>
                        <input class="d-none" type="file" accept="image/*" id="file-input">

                    </div>
                    <div class="col-3 text-center">
                        <h4>03</h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-6">
                        <h5>Custom</h5>
                    </div>
                    <div class="col-3 text-center">
                        <i class="fa fa-camera" style="color:blue; font-size:150%;"></i>
                        <input class="d-none" type="file" accept="image/*" id="file-input">

                    </div>
                    <div class="col-3 text-center">
                        <h4>02</h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-6">
                        <h5>Basic</h5>
                    </div>
                    <div class="col-3 text-center">
                        <i class="fa fa-camera" style="color:blue; font-size:150%;"></i>
                        <input class="d-none" type="file" accept="image/*" id="file-input">

                    </div>
                    <div class="col-3 text-center">
                        <h4>01</h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-6">
                        <h5>Designer</h5>
                    </div>
                    <div class="col-3 text-center">
                        <i class="fa fa-camera" style="color:blue; font-size:150%;"></i>
                        <input class="d-none" type="file" accept="image/*" id="file-input">

                    </div>
                    <div class="col-3 text-center">
                        <h4>07</h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-6">
                        <h5>Luxury</h5>
                    </div>
                    <div class="col-3 text-center">
                        <i class="fa fa-camera" style="color:blue; font-size:150%;"></i>
                        <input class="d-none" type="file" accept="image/*" id="file-input">

                    </div>
                    <div class="col-3 text-center">
                        <h4>05</h4>
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">
                        <h5>Commercial</h5>
                    </div>
                    <div class="col-3 text-center">
                        <i class="fa fa-camera" style="color:blue; font-size:150%;"></i>
                        <input class="d-none" type="file" accept="image/*" id="file-input">

                    </div>
                    <div class="col-3 text-center">
                        <h4>05</h4>
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">
                        <h5>Builder Grade</h5>
                    </div>
                    <div class="col-3 text-center">
                        <i class="fa fa-camera" style="color:blue; font-size:150%;"></i>
                        <input class="d-none" type="file" accept="image/*" id="file-input">

                    </div>
                    <div class="col-3 text-center">
                        <h4>05</h4>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <input class="d-none" id="capture-input" type="file" accept="image/*" id="file-input">

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kitchen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">

                        <div class="feature">
                            <figure class="featured-item image-holder r-3-2 transition"></figure>
                        </div>

                        <div class="gallery-wrapper">
                            <div class="gallery">
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 active transition"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 transition"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 transition"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 transition"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 transition"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 transition"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 transition"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 transition"></figure>
                                </div>
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 transition"></figure>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="controls">
                            <button class="move-btn left">&larr;</button>
                            <button class="move-btn right">&rarr;</button>
                        </div> -->

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary capture">Take a Picture</button>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
<?

?>


