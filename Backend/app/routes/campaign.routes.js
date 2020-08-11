module.exports = app => {
    const campaigns = require("../controllers/campaign.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    // Create a new Campaign
    router.post("/", adminMiddleware.isAuthenticated, campaigns.create);

    // Create a new Campaign this is WIP developing campaigns, branches, and campaign task actions in one go
    router.post("/create",adminMiddleware.isAuthenticated, campaigns.createCustom);
  
    // Retrieve all Campaigns
    router.get("/", campaigns.findAllCustom);

    // Retrieve all Campaigns
    router.get("/custom", campaigns.findAllCustom);
  
    // Retrieve a single Campaign with id
    router.get("/:id", campaigns.findOne);
  
    // Update a Campaign with id
    router.put("/:id", campaigns.update);
  
    // Delete a Campaign with id
    router.delete("/", campaigns.delete);
  
  
    app.use('/api/campaign', router);
  };