module.exports = app => {
    const user_payout_requests = require("../controllers/user_payout_request.controller.js");
    const user_wallets = require("../controllers/user_wallet.controller.js");
    const adminMiddleware = require("../middlewares/admin.middleware.js")

    var router = require("express").Router();

    router.post("/award", adminMiddleware.isAuthenticated, user_wallets.awardTicketAmount);

    router.get("/payout_requests/listall", adminMiddleware.isAuthenticated, user_payout_requests.findAll);
    router.get("/payout_requests/findone/:user_payout_request_id", adminMiddleware.isAuthenticated, user_payout_requests.findOne);

    router.put("/payoutrequest", adminMiddleware.isAuthenticated, user_payout_requests.updatePayoutRequest);
    


    app.use('/merchant/', router);
}