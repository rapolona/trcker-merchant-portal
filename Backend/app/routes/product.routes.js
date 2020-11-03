module.exports = app => {
    const products = require("../controllers/product.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Product
    router.post("/",adminMiddleware.isAuthenticated ,products.create);
  
  
    // Retrieve a single Product with id
    router.get("/:product_id", adminMiddleware.isAuthenticated , products.findOne);
  
    // Update a Product with id
    router.put("/", products.update);
  
    // Delete a Product with id
    router.delete("/", adminMiddleware.isAuthenticated ,products.delete);
  
  
    app.use('/merchant/product', router);
  };