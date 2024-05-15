#include <iostream>
#include <string>
#include <curl/curl.h>
using namespace std;
// Callback function to handle the response from the server
static size_t WriteCallback(void* contents, size_t size, size_t nmemb, std::string* response) {
    size_t totalSize = size * nmemb;
    response->append((char*)contents, totalSize);
    return totalSize;
}


std::string get_hwid() {
    DWORD volumeSerialNumber;

    if (!GetVolumeInformation(L"C:\\", NULL, 0, &volumeSerialNumber, NULL, NULL, NULL, 0)) {
        throw std::runtime_error("failed to get hwid");
    }

    std::string hwid = std::to_string(volumeSerialNumber);
    return hwid;
}
int main() {
    CURL* curl;
    CURLcode res;
    std::string key;
    std::string hwid;
    std::string appid = "d7xeDcK0TM"; // Enter Your AppID
    std::string response;

    // Input the key, HWID, and AppID
    std::cout << "Enter Key: ";
    std::cin >> key;
    hwid = get_hwid();
    std::string postFields = "Key=" + key + "&hwid=" + hwid + "&appid=" + appid;

    curl_global_init(CURL_GLOBAL_ALL);
    curl = curl_easy_init();
    if (curl) {
        curl_easy_setopt(curl, CURLOPT_URL, "https://trinhcuti204.000webhostapp.com/AuthCheck/Authentication.php"); // Your Domain
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, postFields.c_str());
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteCallback);
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, &response);

        res = curl_easy_perform(curl);
        if (res != CURLE_OK) {
            std::cerr << "curl_easy_perform() failed: " << curl_easy_strerror(res) << std::endl;
        }
        else {
            // Print server response
            std::cout << "Server Response: " << response << std::endl;

            // Check the response and inform the user
            if (response == "Logged") {
                std::cout << "Login successful!" << std::endl;
            }
            else {
                std::cout << "Error: " << response << std::endl;
            }
        }

        curl_easy_cleanup(curl);
    }
    curl_global_cleanup();
    system("pause");
    return 0;
}