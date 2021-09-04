<?php
defined('BASEPATH') OR exit('No direct script access allowed');



$route['admin/push-notifications'] = 'admin/push/index';
$route['admin/push-notifications/send'] = 'admin/push/send';

$route['admin/seo/sitemap\.xml'] = "admin/seo/index";


$route['admin']="admin/dashboard";
$route['admin/logout']="admin/login/logout";
$route['admin/user-auth'] = 'admin/dashboard/auth_check';
$route['admin/notification'] = 'admin/dashboard/notification';
$route['admin/all-notifications'] = 'admin/dashboard/all_notifications';
$route['admin/notification-detail/(:num)'] = 'admin/dashboard/detail/$1';
$route['admin/my-profile'] = 'admin/myprofile/index';
$route['admin/change-password'] = 'admin/myprofile/change_password';
$route['admin/vendor-profile'] = 'admin/vendorprofile/index';


///Categories Routes
$route['admin/categories'] = 'admin/categories/index';
$route['admin/add-category'] = 'admin/categories/add';
$route['admin/category-status/(:num)/(:num)'] = 'admin/categories/status/$1/$2';
$route["admin/category-cust/(:num)"] = "admin/categories/cust/$1";
$route["admin/category-customize/(:num)"] = "admin/categories/customize/$1";
$route['admin/edit-category/(:num)'] = 'admin/categories/edit/$1';
$route['admin/delete-category/(:num)'] = 'admin/categories/delete/$1';
$route['admin/trash-categories'] = "admin/categories/trash";
$route['admin/restore-category/(:num)'] = 'admin/categories/restore/$1';
$route['admin/category-display-order'] = 'admin/categories/display_order';



///Brands Routes
$route['admin/brands'] = 'admin/brands/index';
$route['admin/add-brand'] = 'admin/brands/add';
$route['admin/brand-status/(:num)/(:num)'] = 'admin/brands/status/$1/$2';
$route['admin/edit-brand/(:num)'] = 'admin/brands/edit/$1';
$route['admin/delete-brand/(:num)'] = 'admin/brands/delete/$1';
$route['admin/trash-brands'] = "admin/brands/trash";
$route['admin/restore-brand/(:num)'] = 'admin/brands/restore/$1';




///footers Routes
$route['admin/footers'] = 'admin/footers/index';
$route['admin/add-footer'] = 'admin/footers/add';
$route['admin/footer-status/(:num)/(:num)'] = 'admin/footers/status/$1/$2';
$route['admin/edit-footer/(:num)'] = 'admin/footers/edit/$1';
$route['admin/delete-footer/(:num)'] = 'admin/footers/delete/$1';
$route['admin/trash-footers'] = "admin/footers/trash";
$route['admin/restore-footer/(:num)'] = 'admin/footers/restore/$1';



///Sliders Routes
$route['admin/sliders'] = 'admin/sliders/index';
$route['admin/add-slider'] = 'admin/sliders/add';
$route['admin/slider-status/(:num)/(:num)'] = 'admin/sliders/status/$1/$2';
$route['admin/edit-slider/(:num)'] = 'admin/sliders/edit/$1';
$route['admin/delete-slider/(:num)'] = 'admin/sliders/delete/$1';
$route['admin/trash-sliders'] = "admin/sliders/trash";
$route['admin/restore-slider/(:num)'] = 'admin/sliders/restore/$1';



///Regions Routes
$route['admin/regions'] = 'admin/regions/index';
$route['admin/add-region'] = 'admin/regions/add';
$route['admin/region-status/(:num)/(:num)'] = 'admin/regions/status/$1/$2';
$route['admin/edit-region/(:num)'] = 'admin/regions/edit/$1';
$route['admin/delete-region/(:num)'] = 'admin/regions/delete/$1';
$route['admin/trash-regions'] = "admin/regions/trash";
$route['admin/restore-region/(:num)'] = 'admin/regions/restore/$1';




///Purchases Routes
$route['admin/purchases'] = 'admin/purchases/index';
$route['admin/add-purchase'] = 'admin/purchases/add';
$route['admin/purchase-status/(:num)/(:num)'] = 'admin/purchases/status/$1/$2';
$route['admin/edit-purchase/(:num)'] = 'admin/purchases/edit/$1';
$route['admin/delete-purchase/(:num)'] = 'admin/purchases/delete/$1';
$route['admin/trash-purchases'] = "admin/purchases/trash";
$route['admin/restore-purchase/(:num)'] = 'admin/purchases/restore/$1';


///costs Routes
$route['admin/costs'] = 'admin/costs/index';
$route['admin/add-cost'] = 'admin/costs/add';
$route['admin/cost-status/(:num)/(:num)'] = 'admin/costs/status/$1/$2';
$route['admin/edit-cost/(:num)'] = 'admin/costs/edit/$1';
$route['admin/view-cost/(:num)'] = 'admin/costs/details/$1';
$route['admin/delete-cost/(:num)'] = 'admin/costs/delete/$1';
$route['admin/trash-costs'] = "admin/costs/trash";
$route['admin/restore-cost/(:num)'] = 'admin/costs/restore/$1';



///Reviews Routes
$route['admin/reviews'] = 'admin/reviews/index';
$route['admin/review-status/(:num)/(:num)'] = 'admin/reviews/status/$1/$2';
$route['admin/delete-review/(:num)'] = 'admin/reviews/delete/$1';
$route['admin/trash-reviews'] = "admin/reviews/trash";
$route['admin/restore-review/(:num)'] = 'admin/reviews/restore/$1';


///Shipments Routes
$route['admin/shipments'] = 'admin/shipments/index';
$route['admin/add-shipment'] = 'admin/shipments/add';
$route['admin/shipment-status/(:num)/(:num)'] = 'admin/shipments/status/$1/$2';
$route['admin/edit-shipment/(:num)'] = 'admin/shipments/edit/$1';
$route['admin/delete-shipment/(:num)'] = 'admin/shipments/delete/$1';
$route['admin/trash-shipments'] = "admin/shipments/trash";
$route['admin/restore-shipment/(:num)'] = 'admin/shipments/restore/$1';

///Coupons Routes
$route['admin/coupons'] = 'admin/coupons/index';
$route['admin/add-coupon'] = 'admin/coupons/add';
$route['admin/coupon-status/(:num)/(:num)'] = 'admin/coupons/status/$1/$2';
$route['admin/edit-coupon/(:num)'] = 'admin/coupons/edit/$1';
$route['admin/delete-coupon/(:num)'] = 'admin/coupons/delete/$1';
$route['admin/trash-coupons'] = "admin/coupons/trash";
$route['admin/restore-coupon/(:num)'] = 'admin/coupons/restore/$1';


///Stores Routes
$route['admin/stores'] = 'admin/stores/index';
$route['admin/add-store'] = 'admin/stores/add';
$route['admin/store-status/(:num)/(:num)'] = 'admin/stores/status/$1/$2';
$route['admin/edit-store/(:num)'] = 'admin/stores/edit/$1';
$route['admin/delete-store/(:num)'] = 'admin/stores/delete/$1';
$route['admin/trash-stores'] = "admin/stores/trash";
$route['admin/restore-store/(:num)'] = 'admin/stores/restore/$1';

///languages Routes
$route['admin/languages'] = 'admin/languages/index';
$route['admin/add-language'] = 'admin/languages/add';
$route['admin/language-status/(:num)/(:num)'] = 'admin/languages/status/$1/$2';
$route['admin/language-default/(:num)/(:num)'] = 'admin/languages/mdefault/$1/$2';
$route['admin/edit-language/(:num)'] = 'admin/languages/edit/$1';
$route['admin/delete-language/(:num)'] = 'admin/languages/delete/$1';
$route['admin/trash-languages'] = "admin/languages/trash";
$route['admin/restore-language/(:num)'] = 'admin/languages/restore/$1';


///Company Details Routes
$route['admin/company-details'] = 'admin/company_details/index';
$route['admin/add-company-detail'] = 'admin/company_details/add';
$route['admin/company-detail-status/(:num)/(:num)'] = 'admin/company_details/status/$1/$2';
$route['admin/edit-company-detail/(:num)'] = 'admin/company_details/edit/$1';
$route['admin/delete-company-detail/(:num)'] = 'admin/company_details/delete/$1';
$route['admin/trash-company-details'] = "admin/company_details/trash";
$route['admin/restore-company-detail/(:num)'] = 'admin/company_details/restore/$1';


///Payment Methods Routes
$route['admin/payment-methods'] = 'admin/payment_methods/index';
$route['admin/edit-payment-method/(:num)'] = 'admin/payment_methods/edit/$1';

///Invoice Templates Routes
$route['admin/invoice-templates'] = 'admin/invoice_templates/index';
$route['admin/view-invoice-template/(:num)'] = 'admin/invoice_templates/view/$1';
$route['admin/view-invoice-template/(:num)/(:num)'] = 'admin/invoice_templates/view/$1/$2';


///FAQs Routes
$route['admin/faqs'] = 'admin/faqs/index';
$route['admin/add-faq'] = 'admin/faqs/add';
$route['admin/faq-status/(:num)/(:num)'] = 'admin/faqs/status/$1/$2';
$route['admin/edit-faq/(:num)'] = 'admin/faqs/edit/$1';
$route['admin/delete-faq/(:num)'] = 'admin/faqs/delete/$1';
$route['admin/trash-faqs'] = "admin/faqs/trash";
$route['admin/restore-faq/(:num)'] = 'admin/faqs/restore/$1';


///Questions Routes
$route['admin/questions'] = 'admin/questions/index';
$route['admin/add-question'] = 'admin/questions/add';
$route['admin/question-status/(:num)/(:num)'] = 'admin/questions/status/$1/$2';
$route['admin/edit-question/(:num)'] = 'admin/questions/edit/$1';
$route['admin/delete-question/(:num)'] = 'admin/questions/delete/$1';
$route['admin/trash-questions'] = "admin/questions/trash";
$route['admin/restore-question/(:num)'] = 'admin/questions/restore/$1';


///Questions Routes
$route['admin/questionnaires'] = 'admin/questionnaires/index';
$route['admin/add-questionnaire'] = 'admin/questionnaires/add';
$route['admin/questionnaire-status/(:num)/(:num)'] = 'admin/questionnaires/status/$1/$2';
$route['admin/edit-questionnaire/(:num)'] = 'admin/questionnaires/edit/$1';
$route['admin/delete-questionnaire/(:num)'] = 'admin/questionnaires/delete/$1';
$route['admin/trash-questionnaires'] = "admin/questionnaires/trash";
$route['admin/restore-questionnaire/(:num)'] = 'admin/questionnaires/restore/$1';


///Quantity Units Routes
$route['admin/quantity-units'] = 'admin/quantity_units/index';
$route['admin/add-quantity-unit'] = 'admin/quantity_units/add';
$route['admin/quantity-unit-status/(:num)/(:num)'] = 'admin/quantity_units/status/$1/$2';
$route['admin/edit-quantity-unit/(:num)'] = 'admin/quantity_units/edit/$1';
$route['admin/delete-quantity-unit/(:num)'] = 'admin/quantity_units/delete/$1';
$route['admin/trash-quantity-units'] = "admin/quantity_units/trash";
$route['admin/restore-quantity-unit/(:num)'] = 'admin/quantity_units/restore/$1';


///Product Routes
$route['admin/products'] = 'admin/products/index';
$route['admin/product/(:num)'] = 'admin/products/details/$1';
$route['admin/add-product'] = 'admin/products/add';
$route['admin/product-status/(:num)/(:num)'] = 'admin/products/status/$1/$2';
$route['admin/edit-product/(:num)'] = 'admin/products/edit/$1';
$route['admin/delete-product/(:num)'] = 'admin/products/delete/$1';
$route['admin/delete-product-image/(:num)'] = 'admin/products/delete_image/$1';
$route['admin/trash-products'] = "admin/products/trash";
$route['admin/restore-product/(:num)'] = 'admin/products/restore/$1';


///Offers Routes
$route['admin/offers'] = 'admin/offers/index';
$route['admin/add-offer'] = 'admin/offers/add';
$route['admin/offer-status/(:num)/(:num)'] = 'admin/offers/status/$1/$2';
$route['admin/edit-offer/(:num)'] = 'admin/offers/edit/$1';
$route['admin/delete-offer/(:num)'] = 'admin/offers/delete/$1';
$route['admin/trash-offers'] = "admin/offers/trash";
$route['admin/restore-offer/(:num)'] = 'admin/offers/restore/$1';
$route['admin/send-offer-as-newsletter/(:num)'] = 'admin/offers/send_as_newsletter/$1';



///Notifications Routes
$route['admin/notifications'] = 'admin/notifications/index';
$route['admin/add-notification'] = 'admin/notifications/add';
$route['admin/notification-status/(:num)/(:num)'] = 'admin/notifications/status/$1/$2';
$route['admin/edit-notification/(:num)'] = 'admin/notifications/edit/$1';
$route['admin/delete-notification/(:num)'] = 'admin/notifications/delete/$1';
$route['admin/trash-notifications'] = "admin/notifications/trash";
$route['admin/restore-notification/(:num)'] = 'admin/notifications/restore/$1';


///Admins Routes
$route['admin/admins'] = 'admin/admins/index';
$route['admin/add-admin'] = 'admin/admins/add';
$route['admin/admin-status/(:num)/(:num)'] = 'admin/admins/status/$1/$2';
$route['admin/edit-admin/(:num)'] = 'admin/admins/edit/$1';
$route['admin/trash-admins'] = 'admin/admins/trash';
$route['admin/delete-admin/(:num)'] = 'admin/admins/delete/$1';
$route['admin/restore-admin/(:num)'] = 'admin/admins/restore/$1';
$route['admin/admin-detail/(:num)'] = 'admin/admins/admin_detail/$1';
$route['admin/edit-admin-roles/(:num)'] = 'admin/admins/edit_admin_roles/$1';



///Staff Routes
$route['admin/add-staff'] = 'admin/staff/add';
$route['admin/staff-status/(:num)/(:num)'] = 'admin/staff/status/$1/$2';
$route['admin/edit-staff/(:num)'] = 'admin/staff/edit/$1';
$route['admin/delete-staff/(:num)'] = 'admin/staff/delete/$1';
$route['admin/staff/(:any)'] = 'admin/staff/index/$1';


///Vendors Routes
$route['admin/vendors'] = 'admin/vendors/index';
$route['admin/add-vendor'] = 'admin/vendors/add';
$route['admin/vendor-status/(:num)/(:num)'] = 'admin/vendors/status/$1/$2';
$route['admin/edit-vendor/(:num)'] = 'admin/vendors/edit/$1';
$route['admin/trash-vendors'] = 'admin/vendors/trash';
$route['admin/delete-vendor/(:num)'] = 'admin/vendors/delete/$1';
$route['admin/restore-vendor/(:num)'] = 'admin/vendors/restore/$1';
$route['admin/admin-vendor/(:num)'] = 'admin/vendors/admin_detail/$1';

///Emails Routes
$route['admin/emails'] = 'admin/emails/index';
$route['admin/add-email'] = 'admin/emails/add';
$route['admin/email-status/(:num)/(:num)'] = 'admin/emails/status/$1/$2';
$route['admin/edit-email/(:num)'] = 'admin/emails/edit/$1';
$route['admin/delete-email/(:num)'] = 'admin/emails/delete/$1';
$route['admin/trash-emails'] = "admin/emails/trash";
$route['admin/restore-email/(:num)'] = 'admin/emails/restore/$1';


///Pages Routes
$route['admin/pages'] = 'admin/pages/index';
$route['admin/add-page'] = 'admin/pages/add';
$route['admin/page-status/(:num)/(:num)'] = 'admin/pages/status/$1/$2';
$route['admin/edit-page/(:num)'] = 'admin/pages/edit/$1';
$route['admin/delete-page/(:num)'] = 'admin/pages/delete/$1';
$route['admin/trash-pages'] = "admin/pages/trash";
$route['admin/restore-page/(:num)'] = 'admin/pages/restore/$1';


///Users Routes
$route['admin/users'] = 'admin/users/index';
$route['admin/add-user'] = 'admin/users/add';
$route['admin/user-status/(:num)/(:num)'] = 'admin/users/status/$1/$2';
$route['admin/edit-user/(:num)'] = 'admin/users/edit/$1';
$route['admin/delete-user/(:num)'] = 'admin/users/delete/$1';
$route['admin/trash-users'] = "admin/users/trash";
$route['admin/restore-user/(:num)'] = 'admin/users/restore/$1';



///Orders Routes
$route['admin/orders'] = 'admin/orders/index';
$route['admin/orders/clear-filter'] = 'admin/orders/clear_filter';
$route['admin/add-order'] = 'admin/orders/add';
$route['admin/order-status/(:num)/(:num)'] = 'admin/orders/status/$1/$2';
$route['admin/edit-order/(:num)'] = 'admin/orders/edit/$1';
$route['admin/delete-order/(:num)'] = 'admin/orders/delete/$1';
$route['admin/trash-orders'] = "admin/orders/trash";
$route['admin/restore-order/(:num)'] = 'admin/orders/restore/$1';

///Refund Routes
$route['admin/refund-request'] = 'admin/orders/refund_requests';
$route['admin/refund-status/(:num)/(:num)'] = 'admin/orders/refund_status/$1/$2';

///Custom Orders Routes
$route['admin/c_orders'] = 'admin/corders/index';
$route['admin/c_orders/clear-filter'] = 'admin/corders/clear_filter';
$route['admin/c_order-status/(:num)/(:num)'] = 'admin/corders/status/$1/$2';
$route['admin/view-c_order/(:num)'] = 'admin/corders/view/$1';
$route['admin/delete-c_order/(:num)'] = 'admin/corders/delete/$1';

$route['admin/bank-transfer'] = 'admin/corders/bank_transfer';



/// Desginer tasks
$route['admin/pending-tasks'] = 'admin/corders/designer_tasks/0';
$route['admin/late-tasks'] = 'admin/corders/designer_tasks/2';
$route['admin/accepted-tasks'] = 'admin/corders/designer_tasks/1';
$route['admin/view-task/(:num)'] = 'admin/corders/view_task/$1';







///Affiliates Routes
$route['admin/affiliates'] = 'admin/affiliates/index';
$route['admin/add-affiliate'] = 'admin/affiliates/add';
$route['admin/affiliate-status/(:num)/(:num)'] = 'admin/affiliates/status/$1/$2';
$route['admin/edit-affiliate/(:num)'] = 'admin/affiliates/edit/$1';
$route['admin/delete-affiliate/(:num)'] = 'admin/affiliates/delete/$1';
$route['admin/trash-affiliates'] = "admin/affiliates/trash";
$route['admin/restore-affiliate/(:num)'] = 'admin/affiliates/restore/$1';


///// Location Routes
$route['admin/get-states'] = 'admin/location/get_stats_by_country_id';
$route['admin/get-cities'] = 'admin/location/get_city_by_state_id';


///// Blog Routes
$route['admin/blogs'] = 'admin/blogs/index';
$route['admin/add-blog'] = 'admin/blogs/add';
$route['admin/blog-status/(:num)/(:num)'] = 'admin/blogs/status/$1/$2';
$route['admin/edit-blog/(:num)'] = 'admin/blogs/edit/$1';
$route['admin/delete-blog/(:num)'] = 'admin/blogs/delete/$1';
$route['admin/trash-blogs'] = "admin/blogs/trash";
$route['admin/restore-blog/(:num)'] = 'admin/blogs/restore/$1';