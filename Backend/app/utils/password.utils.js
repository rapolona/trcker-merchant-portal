const crypto = require("crypto"); 
exports.hash = (password, callback) =>{
    var salt = password.salt || crypto.randomBytes(32).toString('base64'); 
    var iterations = 10000;
    var passwordString = password.password || password
    crypto.pbkdf2(passwordString, salt, iterations, 64, 'sha512', (err, derivedKey) =>{
        if(err){
            res.status(500).send({
                message: 
                    err || "Something went wrong"
            })
        }
        callback({salt:salt, hash:derivedKey.toString("hex")}) 
    });
}