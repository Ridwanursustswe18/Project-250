import 'dart:convert';
import 'dart:developer';

import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

class DioProvider {
  static const _baseUrl = 'http://192.168.0.104:8000';

  //get CSRF token
  // Future<String> getCsrf() async {
  //   try {
  //     Dio dio = Dio();
  //     final tokenResponse = await dio.get('$_baseUrl/token');
  //     return tokenResponse.data;
  //   } catch (error) {
  //     return '';
  //   }
  // }

  //get access token
  Future<dynamic> getToken(String email, String password) async {
    try {
      Dio dio = Dio();
      //final token = await getCsrf();
      final formData = FormData.fromMap({
        'email': email,
        'password': password,
      });
      //log(token);

      var response = await dio.post(
        '$_baseUrl/login',
        data: formData,
        // options: Options(
        //   headers: {'X-CSRF-TOKEN': token},
        // )
      );
      log(response.data);
      // var res = jsonDecode(response as String);
      if (response.data != '') {
        final SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString('token', response.data);
        return response.data;
      } else {
        return null;
      }
    } catch (error) {
      log("error block");
      return error;
    }
  }
  // Future<dynamic> getToken(String email, String password) async {
  //   try {
  //     Dio dio = Dio();
  //     final tokenResponse = await dio.get('http://192.168.0.104:8000/token');
  //          log(tokenResponse.data);
  //         final formData = FormData.fromMap({
  //     'email': email,
  //     'password': password,
  //     //'token': tokenResponse.data, // include the CSRF token in the request data
  //   });

  //     var response = await dio.post('http://localhost:8000/login',
  //         data: formData,
  //         options: Options(
  //           headers: {'X-CSRF-TOKEN': tokenResponse.data},
  //         ));
  //         log(response.data);

  //     if (response.statusCode == 200 && response.data != '') {
  //       final SharedPreferences prefs = await SharedPreferences.getInstance();
  //       await prefs.setString('token', response.data);
  //       return true;
  //     } else {
  //       return false;
  //     }
  //   } catch (error) {
  //     return error;
  //   }
  // }

  //get user data
  Future<dynamic> getUser(String token) async {
    try {
      var user = await Dio().get('http://192.168.0.104:8000/user',
          options: Options(headers: {'Authorization': 'Bearer $token'}));
      if (user.statusCode == 200 && user.data != '') {
        return json.encode(user.data);
      }
    } catch (error) {
      return error;
    }
  }

  //register new user
  Future<dynamic> registerUser(
      String username, String email, String password) async {
    try {
      var user = await Dio().post('http://192.168.0.104:8000/register',
          data: {'name': username, 'email': email, 'password': password});
      if (user.statusCode == 201 && user.data != '') {
        return true;
      } else {
        return false;
      }
    } catch (error) {
      return true;
    }
  }

  //store booking details
  Future<dynamic> bookAppointment(
      String date, String day, String time, int doctor, String token) async {
    try {
      var response = await Dio().post('http://192.168.0.104:8000/book',
          data: {'date': date, 'day': day, 'time': time, 'doctor_id': doctor},
          options: Options(headers: {'Authorization': 'Bearer $token'}));

      if (response.statusCode == 200 && response.data != '') {
        return response.statusCode;
      } else {
        return 'Error';
      }
    } catch (error) {
      return error;
    }
  }

  //retrieve booking details
  Future<dynamic> getAppointments(String token) async {
    try {
      var response = await Dio().get('http://192.168.0.104:8000/appointments',
          options: Options(headers: {'Authorization': 'Bearer $token'}));

      if (response.statusCode == 200 && response.data != '') {
        return json.encode(response.data);
      } else {
        return 'Error';
      }
    } catch (error) {
      return error;
    }
  }

  //store rating details
  Future<dynamic> storeReviews(
      String reviews, double ratings, int id, int doctor, String token) async {
    try {
      var response = await Dio().post('http://192.168.0.104:8000/reviews',
          data: {
            'ratings': ratings,
            'reviews': reviews,
            'appointment_id': id,
            'doctor_id': doctor
          },
          options: Options(headers: {'Authorization': 'Bearer $token'}));

      if (response.statusCode == 200 && response.data != '') {
        return response.statusCode;
      } else {
        return 'Error';
      }
    } catch (error) {
      return error;
    }
  }

  //store fav doctor
  Future<dynamic> storeFavDoc(String token, List<dynamic> favList) async {
    try {
      var response = await Dio().post('http://192.168.0.104:8000/fav',
          data: {
            'favList': favList,
          },
          options: Options(headers: {'Authorization': 'Bearer $token'}));

      if (response.statusCode == 200 && response.data != '') {
        return response.statusCode;
      } else {
        return 'Error';
      }
    } catch (error) {
      return error;
    }
  }

//logout
  Future<dynamic> logout(String token) async {
    try {
      var response = await Dio().post('http://192.168.0.104:8000/logout',
          options: Options(headers: {'Authorization': 'Bearer $token'}));

      if (response.statusCode == 200 && response.data != '') {
        return response.statusCode;
      } else {
        return 'Error';
      }
    } catch (error) {
      return error;
    }
  }
//   Future<dynamic> getToken(String email, String password) async {
//   try {
//     Dio dio = Dio();

//     // final tokenResponse = await dio.get('http://192.168.0.104:8000/token');
//     // final csrfToken = tokenResponse.headers.value('x-csrf-token'); // retrieve the CSRF token from the response headers

//     final formData = FormData.fromMap({
//       'email': email,
//       'password': password,
//       // 'token': csrfToken, // include the CSRF token in the request data
//     });

//     final response = await dio.post('http://192.168.0.104:8000/login', data: formData);
//     log(response.data);
//     if (response.statusCode == 200 && response.data != '') {
//         final SharedPreferences prefs = await SharedPreferences.getInstance();
//        await prefs.setString('token', response.data);
//        await prefs.setString('id', response.data);
//         return true;
//       } else {
//         return false;
//       }
//     }  catch (error) {
//       var err = error;
//       log(err.toString());
//       return error;
//     }
// }

// //   Future<String> getCsrfToken() async {
// //   log('laravel sucks');
// //   final response = await http.get(Uri.parse('http://localhost:8000/token'));
// //   log(response.body);
// //   return response.body;
// // }

// // Future<http.Response> submitForm() async {
// //   final csrfToken = await getCsrfToken();
// //   final response = await http.post(
// //     Uri.parse('https://example.com/submit-form'),
// //     headers: {'X-CSRF-TOKEN': csrfToken},
// //     body: {'field1': 'value1', 'field2': 'value2'},
// //   );
// //   return response;
// // }
//   //get token
//   // Future<dynamic> getToken(String email, String password) async {
//   //   try {
//   //     Dio dio = Dio();

//   //     //final map = {'email': email, 'password': password};
//   //     final tokenResponse = await dio.get('http://192.168.0.104:8000/token');
//   //     // log(tokenResponse.data);
//   //     // final csrfToken = tokenResponse.data;
//   //     // log(csrfToken);
//   //     final formData = FormData.fromMap({
//   //       'email': email,
//   //       'password': password,
//   //       // 'token':tokenResponse.data,
//   //     });

//   //     var response = await dio.post('http://192.168.0.104:8000/login',
//   //         data: formData,
//   //         options: Options(headers: {
//   //           // 'contentType':"application/json",
//   //           'X-CSRF-TOKEN': tokenResponse.data,})
//   //           );

//   //     if (response.statusCode == 200 && response.data != '') {
//   //       final SharedPreferences prefs = await SharedPreferences.getInstance();
//   //       await prefs.setString('token', response.data);
//   //       return true;
//   //     } else {
//   //       return false;
//   //     }
//   //   }  catch (error) {
//   //     var err = error;
//   //     log(err.toString());
//   //     // print(err.type);
//   //     return error;
//   //   }
//     //   final csrfToken = await getCsrfToken();
//     //   var url = Uri.parse('http://localhost:8000/login');
//     //   final map = {'email': email, 'password': password};
//     //   var response = await http.post(url,
//     //       body: jsonEncode(map), headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': csrfToken});
//     //   log('Response status: ${response.statusCode}');
//     //   log('Response body: ${response.body}');

//     //   if (response.statusCode == 200 && response.body != '') {
//     //     final SharedPreferences prefs = await SharedPreferences.getInstance();
//     //     await prefs.setString('token', response.body);
//     //     return true;
//     //   } else {
//     //     return false;
//     //   }
//     // } catch (error) {
//     //   return error;
//     // }

//   //get user data
//   Future<dynamic> getUser(int userdetail) async {
//     try {
//       var user = await Dio().get('http://192.168.0.104:8000/users/:userdetail/profile',
//          );
//           // options: Options(headers: {'Authorization': 'Bearer $token'})
//       if (user.statusCode == 200 && user.data != '') {
//         return json.encode(user.data);
//       }
//     } catch (error) {
//       return error;
//     }
//   }

//   //register new user
//   Future<dynamic> registerUser(
//       String username, String email, String password) async {
//     try {
//       var user = await Dio().post('http://192.168.0.104:8000/register',
//           data: {'name': username, 'email': email, 'password': password});
//       if (user.statusCode == 201 && user.data != '') {
//         return true;
//       } else {
//         return false;
//       }
//     } catch (error) {
//       return error;
//     }
//   }

//   //store booking details
//   Future<dynamic> bookAppointment(
//       String date, String day, String time, int doctor, String token) async {
//     try {
//       var response = await Dio().post('http://192.168.0.104:8000/book',
//           data: {'date': date, 'day': day, 'time': time, 'doctor_id': doctor},
//           options: Options(headers: {'Authorization': 'Bearer $token'}));

//       if (response.statusCode == 200 && response.data != '') {
//         return response.statusCode;
//       } else {
//         return 'Error';
//       }
//     } catch (error) {
//       return error;
//     }
//   }

//   //retrieve booking details
//   Future<dynamic> getAppointments(String token) async {
//     try {
//       var response = await Dio().get('http://192.168.0.104:8000/appointments',
//           options: Options(headers: {'Authorization': 'Bearer $token'}));

//       if (response.statusCode == 200 && response.data != '') {
//         return json.encode(response.data);
//       } else {
//         return 'Error';
//       }
//     } catch (error) {
//       return error;
//     }
//   }

//   //store rating details
//   Future<dynamic> storeReviews(
//       String reviews, double ratings, int id, int doctor, String token) async {
//     try {
//       var response = await Dio().post('http://192.168.0.104:8000/reviews',
//           data: {
//             'ratings': ratings,
//             'reviews': reviews,
//             'appointment_id': id,
//             'doctor_id': doctor
//           },
//           options: Options(headers: {'Authorization': 'Bearer $token'}));

//       if (response.statusCode == 200 && response.data != '') {
//         return response.statusCode;
//       } else {
//         return 'Error';
//       }
//     } catch (error) {
//       return error;
//     }
//   }

//   //store fav doctor
//   Future<dynamic> storeFavDoc(String token, List<dynamic> favList) async {
//     try {
//       var response = await Dio().post('http://192.168.0.104:8000/fav',
//           data: {
//             'favList': favList,
//           },
//           options: Options(headers: {'Authorization': 'Bearer $token'}));

//       if (response.statusCode == 200 && response.data != '') {
//         return response.statusCode;
//       } else {
//         return 'Error';
//       }
//     } catch (error) {
//       return error;
//     }
//   }

// //logout
//   Future<dynamic> logout(String token) async {
//     try {
//       var response = await Dio().post('http://192.168.0.104:8000/logout',
//           options: Options(headers: {'Authorization': 'Bearer $token'}));

//       if (response.statusCode == 200 && response.data != '') {
//         return response.statusCode;
//       } else {
//         return 'Error';
//       }
//     } catch (error) {
//       return error;
//     }
//   }
// }
}
