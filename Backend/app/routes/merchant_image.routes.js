module.exports = app => {
    const merchant_images = require("../controllers/merchant_image.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Merchant Image
    router.post("/upload",adminMiddleware.isAuthenticated ,merchant_images.create);

    // Get paginated Merchant Images
    router.get("/listall",adminMiddleware.isAuthenticated ,merchant_images.listAllPaginate);
  

  
    // // Delete a Product with id
    // router.delete("/", adminMiddleware.isAuthenticated ,products.delete);
  
  
    app.use('/merchant/merchant_image', router);
  };