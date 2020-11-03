module.exports = app => {
    const campaigns = require("../controllers/campaign.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Campaign
    //router.post("/", adminMiddleware.isAuthenticated, campaigns.create);

    // Create a new Campaign this is WIP developing campaigns, branches, and campaign task actions in one go
    router.post("/create",adminMiddleware.isAuthenticated, campaigns.createCustom);
  
    // Retrieve all Campaigns
    router.get("/campaign/all", campaigns.findAllCustom);

    // Retrieve all Campaigns
    router.get("/custom", campaigns.findAllCustom);
  
    // // Retrieve a single Campaign with id
    router.get("/campaign/:campaign_id",adminMiddleware.isAuthenticated, campaigns.findOne);
  
    // Update a Campaign with id
    router.put("/campaign/update", adminMiddleware.isAuthenticated,campaigns.update);
  
    // Delete a Campaign with id
    router.delete("/campaign",adminMiddleware.isAuthenticated ,campaigns.delete);
  
  
    app.use('/merchant/', router);
  };