var nodemailer = require("nodemailer");

exports.sendMail = (to, from, subject, message, callback)=>{
    var transporter = nodemailer.createTransport({
        host: '172.31.16.201',
        port:25,
        secure:false,
        auth: {
            user: "do-not-reply",
            pass: '29EXt4'
        },
        tls:{
            rejectUnauthorized:false
        }
    });
    var mailOptions = {
        from: from,
        to: to,
        subject: subject,
        html: message
    };

    transporter.sendMail(mailOptions, (err, info)=>{
       if(err){
           console.log(err)
           callback(err); 
       } 
       else{
           callback();
           console.log('Email Sent : ' + info.response)
       }
    })
}

