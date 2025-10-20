<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

require_once 'Product.php';
require_once 'cart.php';
include 'header.php';

$productObj = new Product();
$cart = new Cart();

// Add to cart action
if(isset($_GET['add'])) {
    $cart->addItem($_GET['add']);
    header("Location: cart_page.php");
    exit();
}

$products = $productObj->getAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kitchenware Store</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            color: #2874f0;
            margin-top: 30px;
            font-size: 28px;
        }
        .container {
            width: 90%;
            margin: 30px auto;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        .product-card {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }
        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-card strong {
            font-size: 18px;
            color: #333;
        }
        .price {
            color: #ff6f61;
            font-weight: bold;
            font-size: 17px;
        }
        .desc {
            font-size: 14px;
            color: #555;
            margin: 8px 0 12px 0;
            min-height: 40px;
        }
        .add-btn {
            padding: 10px 15px;
            background: linear-gradient(135deg, #ff6f61, #ff3d3d);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s;
        }
        .add-btn:hover {
            background: linear-gradient(135deg, #ff3d3d, #e60023);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🍳 Explore Our Kitchenware Collection</h2>

    <div class="grid">
        <?php foreach($products as $p): ?>
        <div class="product-card">
            <?php 
            // Assign product images dynamically based on product name keywords
            $img = "https://via.placeholder.com/250";
            $name = strtolower($p['name']);
            if(strpos($name, 'pan') !== false) $img = "https://www.milton.in/cdn/shop/files/71h9Q8NNI8L._SL1500.jpg?v=1736933839";
            elseif(strpos($name, 'knife') !== false) $img = "https://gutereise.in/cdn/shop/products/6_8063_20B-1_1200x.jpg?v=1626892740";
            elseif(strpos($name, 'spoon') !== false) $img = "https://images.unsplash.com/photo-1615486369639-7e6b99f343f4?auto=format&fit=crop&w=600&q=60";
            elseif(strpos($name, 'cup') !== false) $img = "https://images.unsplash.com/photo-1588862030739-45c3ac307d4b?auto=format&fit=crop&w=600&q=60";
            elseif(strpos($name, 'cutting board') !== false) $img = "data:image/jpeg;base64,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";
            elseif(strpos($name, 'bowl') !== false) $img = "https://images.unsplash.com/photo-1585238342023-78e50e4c7088?auto=format&fit=crop&w=600&q=60";
            elseif(strpos($name, 'blender') !== false) $img = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUSExMVFRUVFRUVFRcVFxcVFRUVFRUWFhUVFRUYHSggGBolGxUVITEhJSkrLi4uFx8zODMsNygtLisBCgoKDg0OGhAQGi0lHSUtLy8tLS0uLi0vLS0vLS0tLS0tLS0tKy0tLi0vLS0tLS0tLS83Ly0tLS0uLS0uKy81Mv/AABEIAOEA4QMBIgACEQEDEQH/xAAcAAABBQEBAQAAAAAAAAAAAAACAAEDBQYEBwj/xABJEAACAQIDBAUHCAkCBAcAAAABAgADEQQSIQUxUWEGE0FxkSJSgaGxwdEHFBUyM1OS0iMkQmJygqKy8JPhFhdk00NUY3OzwuL/xAAaAQEBAQEBAQEAAAAAAAAAAAAAAQIDBAUG/8QALhEAAgECBAUCBgIDAAAAAAAAAAECAxEEEiExExRBUVJCkQUVYbHh8DKBImLx/9oADAMBAAIRAxEAPwCnL2kqvpIeqMkpi2+czYqjSMCSNDyXgEQUGEqwgloVNDKCNlg2E6jSkb0L6QCDLrJMlu0R8lhuktNIBGKURpyZNYmpGAc2WSU4bJxjBLyANbGBWI3QHFoB5xYpGV1vJRrI2pxCmYAzKe6B22jteRa3gB1N3ZBKRnB3wQDeUg5WCUhs1oDNIBrQWSOkAmUBE6WE5atbjOgiVuPaQE/ziNKzPFJclzbKI6iQh7b4YaUpIRGWJHvEwgDVDpJaTaSJgIVLTulBMWitEGjAwAXEFYm1hJpKB6W+EzxgYz1AN+npEgI8QdREDIXqrfV18REKyeev4hAJLXhZYBqqN7Ad5AkZxlPz0/EvxgEz6SLPrJKCF/qAuO3KM3sgMB7pAC5EZU7YmQcZD1/ESgauLa3kWYxVCTr4RgYA9uMjJhlpHpADpkxVIJe0S4mn+1mPJbDxJkbsASZV47fNFhcThj9an4s49hlsOjeEri4Z0P7rBh4MDPNUxUIfyTX9GHNdTzuKb7/l4n/mj/p//qKY5yj5DPE4Se2Oo4wUaGm+e02dCgCAWksApIAd8dFhhI6maIPkMc046trHz9kADdHC3jBLnfHUWgGMxTVTULHOyq5B1JAu1lHDhINq4GsozPSdRf8AasO23Ganb1PJhqhUaeQ28Xv12b0DU+ELpw/6K2m9O3i1/fNoyYBrjQi3pEKm1zu7Rwh4xbtfkPZIqQIPpEgJne5JJuTqSdbySkp1IF7f7/AwjhGJJ58DO7DUSFItpz/hOvslAa9JXo4ZqKKytW0zqw+oDZwNbgnd6YWHx6fNhSs61M985+oq9ihgb+A7TxgYSgjUyGDFhkfQDQNUqU2vy8lfSwmn6PLSpq3XYZ6iNdSxItlOmgt75QyDAYGoaKtdahA8oo2bTsJ7d1tYDIYeybU6FWojsr0KrBT5yAAgnnqZDSxYqjrALBtSB2H9q3K95ho0mEafOR1DxjM0mpYR23LpxJCj175hyS1bFyG8iZ5aDZL+dTHex+EY7DqefT/Efyznx6XkiZ49ynqazmZDNEOj9Xzqf4j+WDU6P1uxQ3cy+8iOPSfqXuMy7maJIk2E2nUpm6NbkdR4SzxGyai/WpuP5SR4iVtbByvLNW3RdGWf/GNbzVilH82MU48pS8UZyR7GkhoZChhhp6zZ1Zxxk6kWvOFZ0U2EgJGNoKm++C/fCSCDi0INyjAx8t5QGDESd8dY1iIANPDdZUCMgYOLWYnLob2K8z7PCPH7Bqth1rK7FiraNmABViAANdOd9Z27PB6+lzcDxE0tJf1fd+1UHrJm0jLPKK+x8Vfem6/lVbe1py4vZOJpqXqGnkFi2WqGaxIHkqG1Os9CDnKLEjS3+CZrpzTfqFY1H+tlKgkKwKs3lJexN13wCioYDEOgemaeU3K5nIa19Li8taOwMUKeYsua24M9uzxlr0Bw7dWTnbQgAEkqBlDaJewN338pqMT9TXXUcu3/AGkBhcFhsQjFiwOUAZclYKSews+VSL8z3HdNPRrECiDTQJUX9Ivl63YqMoO61gdZyVjmD2H7an8IPxnTVPlURwSn69ffDKUWITIMag3CoR6re6VOxKlqI07W9st9pfaY4fvg/wBMqNgIDRt+80S2CJamLI3C3+cZCdoNxPjOx8OJG2DE5OCe6FkQDab8TD+nGHH1RnwvKc1TCTnKlHsTIjtTpOR2Ezoo9Mbb1b1SibByF8JOTw9N7onDRtKHTal25h6JMekmDq6Pb0qQfGYBqJEE05y5Kle6uicNG967Z/EeMUwXVxS8t/vL3GT6mzsJIgAkaLJAJ9A6koEJU7ZGiyUGQCtrDVYAhZpSEiprCydsDrI5qQBlex3Qw14B1iU2gHVhDapTP/qL75s2p/oLcHqf3tMNTqeUnJlm/WxRxcaMSR2gEkjTxmk0lqS12ZPCU73B7CfaZken2IQKKSBd+dj+9qpFuNvdNUcUgqOoqISGIt23uNNdNLGVHSLo2+IL1FezWIClRlOXcc2fyQRYbt4JtrPLVx1Ck7Skd1harX8WQ9AMSoVlOW5IK201ChSN+psL+M0+O1VVG9mPsmR6M7HrI1MVksQxZcrKRYAam2u/2zZ4xmQobBlU3INw38hGmvPgOMzUx9GEoxvfN+/fQ1TwlSd1tbuceIoAU+8ueW6V+KP6ekP3KX/xrJ6e1EqslNAWDWseJuSRl5Df2wdtoVxKjgEt3AW909CrQk7JithKlFJzVrlBtT7bGjnTP9Eq+jetI/xt7pY7U1xGM59V/YJWdHPs2/jPsE6PY8y3LbLDyxhHmTQLU5A1KdkEiQHEaAkb4ed+SA6yWBVvh+UhqYblLbJANKRoFN82jS26mKLA77QiYYSMF5TQCQw7xqQkpSABeSU1guLSWmukALLGsIYWRunaOyAK0HNEWuO6CJQOKmXyuGvhNbgMVnqVgQASgP8ASu/nrb0TJG1td3+80WHrEVhcCzURbiRlQ+4z52NcnKMFs/ye7CJWff8A4ZXa2AqdZUcIStwRbXfruHZrvliuFdsnl3okFTc5bMLHLqL7uF+8yMbaAqinUUBLGxBJtbdcdoAvJcdiloELnumtRQBnJ0FraaHfbjc8p82pOpfI469H+9T6c81RLqtfz9jsDpRRbsBZjaxuxGvj8JFXql89QkvamxSmq6fVuSPObwnNVxJxIUUifJ8sqwCsb3GikbheS7NqdVTqNU0ynfv8kkDs7OyeeNGUVnf8+3Xc63g4fXYh2RVZ6qOEyhi5ZRc20sWvbfp6zJukbfrP8IUSzwxWmRUBsGAAFje7lmPfqZX7eYnF3Iucyi3HXQacp7fh0lOu5JWsl+fbY8XxKeaK7GW2g36fE81p/wBk4Ojf2b/xn2Cdu2XRa9UaC9NB/MFAb13nJ0WIy1P4/dP0D2PiLct1GkIiCSI+eYNBCEBBEO2kAawgMId4LQACIIEO8QgA5Y8O0aQEwMQMbLEYKSK8kDSBQZOIILMDvhdbBtpIg+sA6eugq2s5g8Tkyg6mURKt7SA1DElQwDrddLcx7RLJsSeuo71tTCgnc10f2ZQPTKSs5Kt3STHbRs9JgHtTKqRZRfXyrXOpsbeieStScqsZLoemlJKDTdgcfs2+IAYqRdr8TuO7svf1GHj9hUhdi7qANdb5fE6914toE1XpVER1ObyQRZSoIzEsNNxHjOjb1cICrqzK1gAG1JI/Z17O/s7J8zEuoqqSdvp1Pp4as8u+iF0bxFJqOUg51W1wxVmB3ak+q/CTYTJY3ZXGgABNlXXTXeTYa9vKcezaFCipJcXNvrNY7j5NvheQ7apramaLENnPki4LDKN/K84xhxKsoq+p0rVIR1T0RpMO6Fmufq5coOlr/wCxlDt/FojtULZqpN0RRoPNzMd2lj65HiK/VUs1WoQTwsDu3Lw75k6dM1GPlZFJ5s795PZ/lp9jDYCFCTmndvQ+NWxMqqy9DixdTM51voBfjYWJ9MudjYXJT/iN/cJJS2VTXiZ3Lae1s4JAWjyRgIAkuArQ1gCGWEhQssEpEGjmpeABkjWhgRiJbkFaKK4igpIbcfRFUAsJGUzR2awtMgYVLSQVhIHe+kE6Sg63rDdAJB3Gc1+EfNflAJQLb4TEdkZmuBEqy3A2eEhjMtolOkEOLpDjmpooU2LZte0BRc24G5Ep6GLYdWalWpluhfy2sASMxAvbcTD6UVLuBwpnxZgPdK/am7L6PQBabWxlnr3ygYao2Gw64ZRmS5srZDlVWVyvE5iPCeVbRqVTZahrZlOgdm0PK8vtp9I26qhmzXeghWxsBe5qAG3nl5ULjGYC5J3byZlwi3e2pVJpWuBsyh1hUP1r2a4Cs1wd1xwM2dPY/V1EZgaecEgGq1Rgqi5LXvbfumdwmIYIADbQDS/CdOL2sTQqK1866KSdbOpDAegHSVRQzMotsY41qhNzkUkKDwB3mTdG2VnOv1RNF8nuzqOIFRHUZlW40Bv2dszmxKeWvVW1iGy/3fllktCI0REFlHZBYGCCZyNhiJd8G8QMAkaDaApjsZQGI9pEDaOasAltAJgBjCYQBZooOsUpA+s0iJgCGbcZg0NkEZqe6DujBjKDpCCOaPCRrraSFjIB0W/bJFNpFTFheFlvADq6znK2kjLObPrKiFFtmmzVwFVmv1YsoJO8tb1St2ijlrdW9xe4y3IPMDdNPhD+t0//AHaf9pPvkeL+3qfxn2zqtjDMgVq2AyVbC9hlawubm2mmpJ9MQWr5lT8DfCbvZGXr6Oa2Xraea+62cXvfstLgHBnKcq2JYspOq/pEulxqRbNY8DBDy9aVX7t/wH4SYUKnbTf8M9FanhAqPYlbm4u2diKanIewEM4uRpobdkqaygMQpuoJyndcX0Nu6ARdAcY9DEhmpVipUqclJ3Ou7RROE1F+f1iuYBnYgMrAi5JsRbTeZtein2o9ExuLP6/V5sf7v94ewRbu0AiI8oxnFHUYCOTEGkdQygINBLQc8cvKAmaRseEjaoIHWyoh0ITcAXvwl3hejddxc2QHzr38AIHR40l8tz5R3X0sO+a+jiaZ3Pb+a/tnirYvK7I7Qo3V2Zv/AITq/eJ4N8IprvnI+89kU8/PvudOAjzBTDtNTi+iJU+Q915i7jhwBG/XScFXZQGme53arYc763B5WnfnKPf7kWHm9kUYEcmXGzcJTqZlclSDoR6dCPRxnJjMKaZ1sR2MNx7uB5Tqq8HNwvqjDptRzdDnpvyjuQeMckW3xWsPROhiwCHdwkxqcJDh7ScgDWUWIXqHhOPF4jq0L2uR2bu22+d1Ujf2Su2yhNBgo1NgPxCVEaK7D4l2qvUU5GpvT3jMCxU24WsBOWpi6zOxBubm9l4HU2sbS32XsTEEV2WixDVEta25QefOclDDYqi7FaTXO/dxuDv4+jjed0jg2cHzut548B8JNRqYlvq5mtvypmt32WHTwuIGgp1BpbQgcd9jzM6sK+Np3CdcoJuQDoSQASdd9gNeQ4SFOSo+JUXbMo3XZLC/C5WRjG1vPHgPhLPEvjqq5XFZlvfKTpcbtL6wK2ExL5b0X8lSu9jmve7HM58o8rDQaQB9kdIMRQcOCrAbwQN3hOTaG1k644g02LMTcK4RQe7Ix7J2nY2McZRQc8NEHLjOba3RLGqmZsO4APaUHYf3pbELXB4vrEDgZbgaXvbQHfYcZITOPZNBkpKrixsO0H9kDsPKdRnB6M7rYIQXMRMjqNABZpBUqQKlTWclatNEJXeBnPAzhqVuMbNwnOUmjrTpxluzZ4faLEBBoO06Wtyltha6rYaeqeeUsTOk4y2l582rhsx7skZLc9E+kBFPPfn3P1xp5/l6Lwo9z12tQxtNiDUokXOUtcZhxN9x5RmqVqi2ajh6tuFU7924obTvxu0MHiVahVs4vqjo1+RW4vfmJRt0WoowbCVQljqHXrCt9wBuCnZ/m/m4R3T/AH9+hpS8lb+h8Vsx2ByUkonVmzZWVj2gNbye3eLSnwtA6ioy2P1luGHjuHovITilar1NWu1BwxVjVp2puw0sXucnZY+UPGXB2BjVuoFKxtZg+a47ASQpHZuW0OFSx0TitGypq7HXN5LFRpv8vvy2t65zY7DhBYEnje3hbslqdmVw2WoEU77Z1LEXtdQDfxlkmywRqiseYvpyuN/Od6eJqRks7ukcKtGnb/ExuAoNUbKthYak6ADnJXWxtcHS+l/eJqgmd1pIBoRm3ZVA9mv+aw63RFCxbrrXJNgosL+meqnis8nfRHnlRstNzHVAJyY42UD94e+bduiVP7/+kfGZ/pjsVcPSpstTPmqhbWtYZWN9/L1z0060JSSTOM6clFuxoui5/QOeL+6UeN+0bvlz0aP6t/N7hKPGHy2757uh4+pCtzoIqmIppfPVUWNm8pRlNr2JcgXt2XB5Th21tE0MKzqbPUJRT2jKVzf3oR3czMFglotc1Wqg30yIr3vxLONb3hHTSKu1dnquA2jh3y+WLMbAhlLFt9gFuCe21yx7BLOvhShGoZWF1YbmH+EeM8WxaUQoNNqha4vmRVAFtbFWJJvbsnqHQnbJxGEZKhuyG4PbmBOY+lSD3s57Y6jSUb2s0ajZ31hLLpSL0DKzZ51EtOkWtE90pyPMm3Du95kNQiWmA2Q2IuEIBUEm/NjOluhVfz18DPHVqwjJps9cIScdEZ56s5KtaaV+hVfzl8DOat0JxHnJ6xOfMU/I1wp9jK1KsgqNNNU6EYniniZC/QfFfueMvMUvIcGfYy1SAKltOyad+guL4L4yE9BcZ5g8Y49J+pBU6id0jP8AWwalXThNA3QTG/djxEQ6EYvtpf1CTjUl6kdUqr0sZvrecaar/gbE/cnxHxil5il3NcOZU/S1QixINt2g07tNPRLDD9LMUqCmtTyVBAGVDv36lbk8zM4Ghq89HCg/SvY8PEn3fuXVba9So2ZyGJUKb7iBuBG495F5Y4DpTiqShadUqo3D64A4APcKO60zCvJOtjhQ2svYnEn3Zf4rphi8xYVAGbUkKtyQLC5twlbi+lWMcZWxDkbrA2HqlXVqXkSLcwqVNbRXsM8n1Z6Z8l9CpUeo3lMEQDtNi7X/APqZ6E+CfzW8DK75M8AuGwYLkLUrHOwO8LayA+i5/mmu+dp548Z5quEjUlmbPRTxEoKxl/o6r5j+BmR+Umm1OjRzBhet2gjcjce+erfPKfniebfLViA1PChTf9K5P4Rb3zFHAQpzUk3oaqYuU4uLRN0bb9UXvPsEo8WfKbvl30eFsInplFifrN3z6XQ8JSdJKBqYZgNTSPWW/cP2hHG2WmTwAY9kw6aT0kEggg2I3GVGO6O0KhuuaixNyEUVKR42QspTuBI5CZNXMZU1no/QDCmnhmci3WNZOYW4du4kqvejSr2d0Uoqbuz1Rvy5RST+azMx9BHfNdRO4aAAAAAWAAFgqgaAAdkqFy6wJ1Et9t/Yt3SlwJ1EutrfYnum0YMz0NNnfQnQjQE/tA9k1gfk34G+EzvyfOFrVb8D7Vm++dr/AIDPnV8HGpPNc9lKu4RtYpc/Jvwt8JyYxSSLK5/lb4TTfPE4+oxfPU4+o/CcH8Ni9MzOscY1rYynUN5r/hb4QWwr+a/4W+E13z5OPqPwi+fpxPgZj5VDyZvn5djKJhX81/wt8JMlCoP2W/CZpfpCnxPgYvpCnxPgZV8LgvUw8dJ9EZxqFTzW8DIvmlXzW8DNR9IJz8DF9IJz8JflkPJk56XZGZ+a1eDeBimm+kE5+Bik+WQ8mXnpdkfJQqwhUno//LCn99V/o/LBPyZ0x/41XwX8s+rc+fY89FSP1s9DX5OKX31X+n8slX5N6H3lQ+kfCLix57g6LVXWmguzGwHP3T0zov0No0SKmIdajjUIPs1PEk6ufAd8fB9A6VI5lL343MuKGwyv7RPeTFy2NAMYnGOMWnGVVPZR4yb6JPGAWYxacZgflPxSF8OmXN9ZvrEW1t2TUnZD+dKHpD0VrVrWdLruLXuO4wDs2U9sOoCHLbcKh94lZWNK5vTqeiqv/bnPQ6O7TRcorq1t36VlFuGXqz7Zz1Oju1L/AFsP6XYn+wTV0ZsdXVUvu6v+qv8A24Qo0/u6v+ov5JxDYG1OOG/E35YY2JtX/pvxt+WS6FiwppT8yp/qD8k6UFLzKn+oPySn+htq/wDTfjb8sMbI2rww342/LF0LGnwJS48lvTU+Cy7xxvTN1FrdjG/jaYShsvbF9Pm34z+WXVPZm1Gp2qOgOm6qMtu3Tqr+uW4sLo4yCsVSnlY5rsXLG1t1rAdk1gvKbYmyDSOZgpY7yCT7QJdgcpkog3OEB3xWHCPeACV74NhDiEAHKIxA4QyI+WCkVorSXKY9pAQ5Y8lyxQCpFI84/VScDkfCGF5QU5RRHD2x+qHCdPVmOKUA5epHOGtMc50dTHFCARCnCC85J1PKLqu/wgEeWA1PnJzTgml3wQi6nnANAToFPvgtT5H1wU5zREbq50dXy9RjdWeHqgEQSTIgjCmeB8IYU8/CATUhJzunMoP+CSXMEGKwgsG3fEB3ykDyx8ogZTziKmAFaKB1Z5xxT74AcV4BSKxgofpjWPGDfvjgnnIB9YormKAUo3iTmNFIaCHZGaKKUEh7JHFFIBk3x6nuiigDCGYooA6x1iigDVd3pgxRQBjDTdFFAF2mON0UUAJd8Jo0UAB/dAMUUgHpx3/zwiigBUpE0aKAO0dN0UUAgiiimQf/2Q==";
            elseif(strpos($name, 'tawa') !== false) $img = "https://images.unsplash.com/photo-1588166501264-5326e9b7b4df?auto=format&fit=crop&w=600&q=60";
            ?>
            
            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
            <br><br>
            <strong><?php echo htmlspecialchars($p['name']); ?></strong><br>
            <span class="price">₹<?php echo $p['price']; ?></span>
            <p class="desc"><?php echo htmlspecialchars($p['description']); ?></p>
            <a href="?add=<?php echo $p['id']; ?>">
                <button class="add-btn">🛒 Add to Cart</button>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>



