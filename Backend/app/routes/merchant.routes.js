module.exports = app => {
    const merchants = require("../controllers/merchant.controller.js");
    const branches = require("../controllers/branch.controller.js");
    const products = require("../controllers/product.controller.js");
    const campaigns = require("../controllers/campaign.controller.js");

    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();

    // Create a new Merchant
    router.post("/", merchants.create);
  
    // Retrieve all Merchants
    router.get("/",  merchants.findAll);
  
    // Retrieve a single Merchant with id
    router.get("/profile",adminMiddleware.isAuthenticated ,merchants.findOne);

  
    router.get("/branches",adminMiddleware.isAuthenticated ,branches.findAll);

    router.get("/products",adminMiddleware.isAuthenticated ,products.findAll);

    router.get("/campaign",adminMiddleware.isAuthenticated ,campaigns.findAllCustom);

    
    router.post("/campaign/create",adminMiddleware.isAuthenticated, campaigns.createCustom);

    // Update a Merchant with id
    router.put("/profile", adminMiddleware.isAuthenticated ,merchants.update);
  
    // Delete a Merchant with id
    //router.delete("/delete", merchants.delete);
  
    // // Create a new Merchant
    // router.delete("/", merchants.deleteAll);

    // Create a new Merchant
    router.post("/loadData", merchants.loadData);

    router.post("/loadTasks", merchants.loadTasks);

    router.get("/dashboard/activecampaign", adminMiddleware.isAuthenticated, campaigns.getActiveCampaigns);

    router.get("/dashboard/totalrespondents", adminMiddleware.isAuthenticated, campaigns.countRespondents);

    router.get("/dashboard/countcampaign", adminMiddleware.isAuthenticated, campaigns.countCampaign);
  
    app.use('/merchant/', router);
  };