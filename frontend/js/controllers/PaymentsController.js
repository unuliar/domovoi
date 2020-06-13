app.controller('PaymentsController', function ($scope, $rootScope, $cookies) {
    $rootScope.checkAuth();

    $rootScope.pageTitle = "Платежи за ЖКХ";

    $scope.activePayment = null;

    $scope.summariesSum = obj => {
        var sum = 0;
        for( var el in obj ) {
            if( obj.hasOwnProperty( el ) ) {
                sum += parseFloat( obj[el] );
            }
        }
        return sum;
    };

    $scope.payments = [
        {
            number: "1000362421",
            period: "за май 2020",
            summaries: {
                hot_water: 4.3,
                cold_water: 9.7,
                energy: 82.03,
                energy_nigh: 83.837,
                waterback: 16.5,
            }
        },


        {
            number: "1000362523",
            period: "за апрель 2020",
            summaries: {
                hot_water: 4.1,
                cold_water: 9.8,
                energy: 85.03,
                energy_nigh: 73.837,
                waterback: 12.5,
            }
        },


        {
            number: "1000361345",
            period: "за март 2020",
            summaries: {
                hot_water: 5.3,
                cold_water: 8.7,
                energy: 81.03,
                energy_nigh: 98.837,
                waterback: 13.5,
            }
        },

        {
            number: "1000351675",
            period: "за февраль 2020",
            summaries: {
                hot_water: 5.3,
                cold_water: 8.6,
                energy: 79.03,
                energy_nigh: 83.837,
                waterback: 15.5,
            }
        }
    ]

    $scope.setActivePayment = payment => {
            $scope.payments.forEach((payment) => {
                payment.classList = {};
            });

        payment.classList = {"list_item_active": true};

        $scope.activePayment = payment;
    };
});