module.exports = app => {
    const user_wallet = require("../controllers/user_wallet.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")
    var router = require("express").Router();
  
    router.post("/editamount", adminMiddleware.isAuthenticated ,user_wallet.editWallet);
  
  
    app.use('/merchant/', router);
  };