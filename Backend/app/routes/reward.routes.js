module.exports = app => {
    const user_payout_requests = require("../controllers/user_payout_request.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")

    var router = require("express").Router();



    router.get("/payout_requests/listall", adminMiddleware.isAuthenticated, user_payout_requests.findAll);
    router.get("/payout_requests/findone/:user_payout_request_id", adminMiddleware.isAuthenticated, user_payout_requests.findOne);

    


    app.use('/merchant/', router);
}