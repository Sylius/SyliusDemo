import '@vendor/sylius/mollie-plugin/assets/shop/entrypoint';
import { app } from '@sylius-ui/shop/Resources/assets/app';
import PayPalDemoCompleterController from './controllers/paypal_demo_completer_controller';

app.register('paypal-demo-completer', PayPalDemoCompleterController);

import '../scripts/info_box'
import './styles/main.scss'
