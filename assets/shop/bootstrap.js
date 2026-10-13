import { startStimulusApp } from '@symfony/stimulus-bridge';
import ProductShowImagesController from '@sylius/shop-bundle/controllers/ProductShowImagesController';

export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

app.register('product-show-images', ProductShowImagesController);
