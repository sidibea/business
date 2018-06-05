<?php
/**
 * The template for displaying the shipping label
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/order
 *
 * @package    WCVendors_Pro
 * @version    1.0.4
 */
?>

<html>
<head>
	<title></title>
	<style type="text/css">
	body { 
		font-family: "arial"; 
	}

	.left { 
		float: left;
	}

	#shipping_label { 
		border: 3px solid #000;
		width: 600px;
		height: 200px;
		padding: 10px 40px; 
		margin: 25px 50px;
	}

	#to { 
		width: 50%;
	}

	#from { 
		width: 50%;
	}

	#picking_list { 
		clear:	both;
		margin: 30px 50px;
	}

	#scissors {
	    height: 43px; /* image height */
	    margin: auto auto;
	    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAArCAYAAAA+EwvfAAAAAXNSR0IArs4c6QAAActpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDUuNC4wIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIgogICAgICAgICAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyI+CiAgICAgICAgIDx4bXA6Q3JlYXRvclRvb2w+QWRvYmUgSW1hZ2VSZWFkeTwveG1wOkNyZWF0b3JUb29sPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KKS7NPQAABXBJREFUaAXNmW2IFWUUx925e2GR9aU3s1yLqAhDjFA2IxXTIJSoD70oBEq+YIpEyBILmR+UXtH8ooSi9CFCERZLIakWFVp0daG1MmRRyXKxZSVNdlfU3bu337ndZzg8M8/Mnd25l3vh7HPmvPzP/3nmmZkzs2PGqF8mk3kF+cbzvG7GfqQXaeP4PcImqtCqU7MQ3YvkXcIkumpra+dVHXMhZJOH7Blsh5CTyB3ETKw/m83OrqpJsKpzFMGb6G9C0DMkIfw0thMmhsn9gq/O+Cs8ZgP1ILZDkWsOBPxveBDif5s4xlcdcWmbG6j1FrKf+p1IF/oJFn0r8izFPNk+RxHZIn0YJrsYkLy5GCexu1xxKdnrqPchdf5RNc029kdiNtZS0GyXIfRBF4GamprflG+c0lNV2bIzh4eHdwL6TAzwbuL2CPnuYuBEZvucKymfzz9gfOhdRk9zZEWbIPUTmFHkr+J/PZfLrWHskS201JwmAH7FOB4J/IhpN3GsUlSBQG4JhqlgHzb4EeMxsB638epI6FBJbRCcpYLu4oLZrvyH8Jltp8JGpoK7hIX7U+H7e1zZcsR8QoXgXahYdhrB8tQ1yYPop5FWEuWpXLCjdxJ/78ioBrLqwd1jsF0jNS+zgC8Gsm0Dq94IyCkXUNHeQl7Gzk16DKF5EPs5ppYs2mGwH0mCT07mZeQIop/A5szIeBICLyQBVbHw9j4AI4doTFsfJG6DyhuROh2Qz5BLjmL7QJ2eAPkxcH5wYPkToN55FmhhAtzY0PEUXYL47YQiIa3HXhAejkIhZgWirzGfsMIS29fg3BeFNRqfR4HlrFCPVVQK92J/B/Aaq8AEfF+FxNsTGCB/nZVbtsNHISSdqk1CjlvlZiCV0RdD6qwjTud2kFP5DhdirlvgAL5vSyAuk9jBXMeWbaljgGVL7SuRqF5x0aVJWx2DXxG3vMn9mHAS0usE2oGKsHUUaWCvu261euX7iJN2INWXoTR6mm5a7YuOyflmOtgeOs0vMNzyjdWgsKrvJthC8ixYWg28Cxwgsxi5nWAChS3Fk3YbAKFte8UmB4m5EO9LSt7Ec+bOQHZGxQjrQpCfD5Grhow1HsD/PLZWy64vaKNfJ2aFxi63zsJ5myh6yyaH/S9sbygCErsem/RLhrBrlB7oHpWbvlpc9eMhZKQ1lifq/WFVpU1gIrG9PzHnZFuGYYzGNg5y8s78XQhxWc0Wijo/BKjC9cRtc2Dos3KbiTSTZzeFCqo09UmANiMXHEXlpSb+Nc+qBZY0eH84MPVEjpA6xUqPP4SUXHz7kcAel6IU70HWgjSa18opYB0UvCihzkX8i+JZFyMg/3kUoPgAbSoZMCYQvOXIjbiawgso+QDn/kHsfQvoOLZmZAN2vzXm+DIo09xIiT0zwG+zaoedmaMgPxGKzl1iJgBDRZAhSK6xA7G9rYrIx6U0eihTJgu+LOCgqhE2CWnBl5kkf8S4yyQC9JHvsBRi2k0crtSfoGyV+dT/3dSIGOXDcr1Pj0DzVe46xkm+w1IA32JA0ddb7rQO76aG601Pn5VT7JzC502PNneCVGeU/d0bwSSnfKEPLeUfqXqNj7arSH4NPlciQBppzdtZyCbZy/0SSE8/mcH5jzz8CyROfuiXCkqZ/jCJFgg2MomtSOi7BvZzlB+Qrwdfqq3xcRgn/CtNDKP8I6QhLK5MtrFcH3NZ7bXIRtm+HMvTP1uox8EcRS7PsfTqcqucxD57ioRP8fufGTneUkispj+Qkg5TXyRyS5NWd9iyH4R3qu+0qa0Dk5BXw2sWYTMpeSGXlY9+IqbGpnQgu+t7CKIvcZHKfV7uTv9ysZzlgvoe/ULpsJWL/A9+zoXsc28J9AAAAABJRU5ErkJggg==');
	    background-repeat: no-repeat;
	    background-position: left;
	    position: relative;
	}

	#scissors div {
	    position: relative;
	    top: 50%;
	    border-top: 3px dashed black;
	    margin-top: -3px;
	}

	</style>
</head>
<body>

<div id="shipping_label">
	<div id="to" class="left"> 
		<h1><?php _e( 'Ship To:', 'wcvendors-pro'); ?></h1>
		<address>
			<?php $address = ( wc_ship_to_billing_address_only() ) ? $order->get_formatted_billing_address() : $order->get_formatted_shipping_address(); ?>
			<?php echo $address; ?>
		</address>
		
	</div>

	<div id="from" class="left">
		<div id="vendor"> 
			<h3><?php _e( 'From:', 'wcvendors-pro'); ?></h3>

			<address>
				<?php echo $store_name ?><br />
				<?php echo $store_address1; ?><br />
				<?php if ( !empty( $store_address2 ) ) echo $store_address2. '<br />'; ?>
				<?php echo $store_city; ?><br />
				<?php echo $store_state; ?>, <?php echo $store_country; ?><br />
				<?php echo $store_postcode; ?><br />
			</address>
		</div>
	</div>
</div>


<div id="scissors" style="clear:both;">
    <div></div>
</div>

<div id="picking_list">
		<h1><?php _e( 'Picking List', 'wcvendors-pro'); ?></h1>

		<?php echo $picking_list; ?>

</div>

</body>
</html>