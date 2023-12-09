from ppadb.client import Client as AdbClient
import time
import sys
import threading
import datetime
import socket
import os
from random import randrange
import pyautogui

import pickle

import subprocess
import tkinter as tk
import cv2

import ftplib   #pip install pyftpdlib

import win32gui

class ADBTest:
    
    def __init__(self) :
        print('ADBTest ready')
        self.set_value = 0
        self.set_order = 0
        self.install_value = 0

    def connect(self):
        client = AdbClient(host="127.0.0.1", port=5037) # Default is "127.0.0.1" and 5037
        devices = client.devices()
        df = []
        global seriallines        
        
        if len(devices) == 0:
            print('No devices')
            quit()
            
        else:
            df_cnt = 1
            if self.set_value == 0:
                #device_usb_args = "adb devices -l"
                #device_usb_ = device_usb_args.split(" ") 
                #usb_ids = subprocess.check_output(device_usb_)               
                #usb_lists = usb_ids.decode('utf8')
                #usb_lists = usb_lists.split("\r\n")
                

                for device in devices:                    
                    device_model_args = "adb -s "+ str(device.serial)+" shell getprop ro.product.model"
                    device_model_ = device_model_args.split(" ")               
                    try:
                        model_output = subprocess.check_output(device_model_)
                    except:
                        print("ADB txt error")
                                                            
                    model_d = str(model_output).replace("b'","")               
                    model = model_d.replace("\\r\\n'","")                     
                    if df_cnt == len(devices):
                        last_s = ''
                    else:
                        last_s = '\n'

                    #for usb_string in usb_lists:
                    #    if device.serial in usb_string:
                    #        transport_id_ = usb_string.split("transport_id:")
                    #        transport_id = transport_id_[1]
                            

                    device_number = self.set_value + 1                        
                    df.append(device.serial+" "+model+" "+str(df_cnt)+last_s)#+" -"+str(transport_id)
                    viewnumber = 0
                    if int(self.install_value) >= 1 and int(self.install_value) <=5 :
                        try:
                            if self.install_value == 5 or self.install_value == 1:
                                img = cv2.imread('C:/xyz/bgimage/tpl.jpg', cv2.IMREAD_UNCHANGED)
                                white= (255, 255, 255)
                                font =  cv2.FONT_HERSHEY_DUPLEX
                                # 이미지에 시리얼 합성하기
                                img = cv2.putText(img,"Serial : "+str(device.serial), (40, 160), font, 2, white, 2, cv2.LINE_AA)
                                # 이미지에 글자를 넣어주기 (serial.txt 기준으로)
                                for serialline in seriallines:
                                    if device.serial in serialline:
                                        device_ = serialline.split(" ")
                                        viewnumber = device_[2].replace("\n","")
                                #str(df_cnt)
                                print(str(viewnumber))
                                img = cv2.putText(img,str(viewnumber).zfill(2), (200, 700), font, 17, white, 5, cv2.LINE_AA)
                                                                
                                cv2.imwrite("C:/xyz/bgimage/"+str(device.serial)+".jpg",img)
                                time.sleep(2)
                                
                                bg_jpg_args = "adb -s "+str(device.serial)+" push C:/xyz/bgimage/"+str(device.serial)+".jpg /sdcard/Download/"
                                bg_jpg_ = bg_jpg_args.split(" ")
                                bg_push = subprocess.check_output(bg_jpg_)                
                                print(bg_push)
                                time.sleep(2)
                                bg_set_args = "adb -s "+str(device.serial)+" shell am start -a android.intent.action.ATTACH_DATA -c android.intent.category.DEFAULT -d file://mnt/sdcard/Download/"+str(device.serial)+".jpg -t 'image/*' -e mimeType 'image/*'"
                                bg_set_ = bg_set_args.split(" ")
                                bg_set = subprocess.check_output(bg_set_)
                                print(bg_set)
                                                                
                                time.sleep(2)                                                   
                           
                            if self.install_value == 5 or self.install_value == 2:
                                os.system('adb -s '+str(device.serial)+' install "C:/xyz/adbkeyboard/ADBKeyboard.apk"')
                                time.sleep(2)
                            
                            if self.install_value == 5 or self.install_value == 3:
                                os.system('adb -s '+str(device.serial)+' install "C:/xyz/vpn/OpenVPN for Android_v0.7.41_apkpure.com.apk"')
                                time.sleep(2)
                        except:
                            pass
                        time.sleep(2)                            
                    df_cnt += 1
                    
                if self.install_value == 4 or self.install_value == 5 :
                    with open('./serial.txt','w') as f:
                        f.writelines(df)
                        f.close()
                        
            device = devices[int(self.set_value)]
            return device, client

                              

        
    def device_screen_capture(self, device):
        result = device.screencap()
        with open('s_'+str(device.serial)+'.png', 'wb') as fp:
            fp.write(result)
        fp.close()    
               
        
    def work_start(self):
        global seriallines
        global com_check
        global xyzsize
        device_number = self.set_value + 1
        find_order = ""
        for serialline in seriallines:
            order_device_ =  serialline.split(" ")
            find_order = order_device_[2].replace("\n","")
            if str(device_number) == find_order:
                order_serial = order_device_[0]
                print("찾는 번호 "+str(device_number)+" txt 번호 "+str(find_order))
                break
            
        yposition = 80                
        xcnt = self.set_value
        if self.set_value >= 14:
            xcnt = self.set_value -14
            yposition = 720
        elif self.set_value >= 7:
            xcnt = self.set_value - 7            
            yposition = 400  
        xposition = 30 + 180 * xcnt
        
        try:
            wmsize = ""
            if com_check in xyzsize:
                wmsize = "-m 2280 "
            while True:
                print(str(device_number)+"bee")
                if not window_exists(str(device_number)+"bee"):
                    os.system("scrcpy --window-title "+str(device_number)+"bee "+str(wmsize)+"--window-x "+str(xposition)+" --window-y "+str(yposition)+" --window-width 170 --window-height 280 -b 600 --max-fps 6 -s "+ str(order_serial)) #--always-on-top
                    pass
                time.sleep(10)
                    
        except Exception as e:
            print(e)
            print("check adb syntax")
                    
        time.sleep(2)
        

def job(a,i):
    
    ADBbot.set_value = a-1
    ADBbot.set_order = i-1 
    ADBbot.work_start()

def ftp_pyfile_upload(ver):
    pver = ver.replace('.','')
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(" 파이썬 파일 "+str(pver)+" 업로드 시작")
    #mtypepath = os.getenv('USERPROFILE')+"/Desktop/chinaM/koreaM.py"
    mtypepath = "C:/xyz/xyz.py"
    print(mtypepath)
    
    ###############
    # 파일 업로드
    ###############
    try:
        # ftp 연결
        with ftplib.FTP() as ftp:
            ftp.connect(host=host,port=21)
            #ftp.encoding = 'utf-8'
            ftp.encoding = 'euckr'
            s = ftp.login(user=user,passwd=passwd)
            ftp.cwd('/mobile_type/')  # 현재 폴더 이동
            print(ftp.dir())
            # 파일업로드
            with open(file=mtypepath, mode='rb') as wf:
                ftp.storbinary('STOR xyz.py' , wf)
            print(ftp.dir())
     
    except Exception as e:
        print(e)
        print( "FTP 파이썬 파일 업로드 에러")
        
    print( " 버전 "+str(pver)+" 업로드  완 " ,end = " ")
    print(datetime.datetime.now())
    print("-----------------------------------------------------")
    

def ftp_pyfile_download():    
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(" 파이썬 파일 다운로드 시작 ")
    #mtypepath = os.getenv('USERPROFILE')+"/Desktop/chinaM/koreaM.py"
    mtypepath = "C:/xyz/xyz.py"
     
    ###############
    # 파일 다운로드 
    ###############
    try:
        # ftp 연결
        with ftplib.FTP() as ftp:
            ftp.connect(host=host,port=21)
            #ftp.encoding = 'utf-8'
            ftp.encoding = 'euckr'
            s = ftp.login(user=user,passwd=passwd)

            ftp.cwd('/mobile_type/')  # 현재 폴더 이동                        
            with open(file=mtypepath, mode='wb') as wf:
                ftp.retrbinary('RETR xyz.py' , wf.write)
            print(ftp.dir())
     
    except Exception as e:
        print(e)
        print("FTP 파이썬 파일 다운로드 에러")
        
    print( " 파이썬파일  다운로드  완 " ,end = " ")
    print(datetime.datetime.now())
    print("-----------------------------------------------------")



def window_exists(title):
    try:
        hwnd = win32gui.FindWindow(None,title)  
        if hwnd == 0:
            return False
        else:
            if win32gui.isWindowVisible(hwnd):
                return True
            else:
                return False    
    except Exception as e:
        print(e)
        return False


if __name__ == "__main__":

    os.system("pip install win32gui")
    
    os.system("adb kill-server")
    time.sleep(1)
    os.system("adb start-server")    
    ver = '1.0.0.0.2'    
    com_check = socket.gethostname()
    print("")
    print("################################")
    print("XYZ - "+str(com_check)+" "+str(ver) + " 버전 시작 ")
    print("")

    xyzsize = ["k999"]
    
    while True:
        if com_check == "k100":
            ftpsync = input("파이썬파일 서버로 업로드는 1번  아니면 엔터 : ")
        else:
            ftpsync = input("파이썬파일 다운로드는 1번)  아니면 엔터 : ")
            
        if ftpsync == "":
            break
        else:
            if com_check == "k100" and str(ftpsync) == "1":
                ftp_pyfile_upload(ver)
            else:
                ftp_pyfile_download()
                
            break
        
    print("")

    try:
        if os.path.isfile('c:/xyz/serial.txt'):
            f = open('c:/xyz/serial.txt')
            seriallines=f.readlines()
            f.close()
            total = len(seriallines)
        else:
            iniclient = AdbClient(host="127.0.0.1", port=5037) # Default is "127.0.0.1" and 5037
            devices = iniclient.devices()
            total = len(devices)
            
    except Exception as e:
        print(e)        
        print("serial.txt가 없습니다. serial.txt생성 번호 4를 입력하세요")
        pass

    ADBbot = ADBTest()
    
    
    while True:
        setting = input("1- 배경이미지만 , 2- 한글키보드만 , 3- vpn만 , 4- serial만 . 5- 전부 셋팅 , 아니면 엔터 : ")    
        if setting == "":            
            break
        elif int(setting) >= 1 and int(setting) <= 5 :            
            ADBbot.install_value = int(setting)
            ADBbot.connect()
            break         
    ADBbot.install_value = 0
    threads = [] 
    for i in range(1,total+1):
        a = 0
        for x in range(0, total):
            lineinfo = seriallines[x].split(" ")
            
            if int(lineinfo[2]) == i:
                a = int(x)+1
                break
        if a == 0:
            print("serial.txt를 확인하세요")
        if com_check =="k100":
            print("@@@@@@@@@ "+str(a) +" - " +str(i))     
        threads.append(threading.Thread(target=job, args=[a,i]))
        time.sleep(0)
    intx = 0
    interval = 3
    for thread in threads:
        thread.start()
        
        if intx>=0:
            #interval += 3
            if com_check =="k100":
                print("시간간격 : "+str(interval)+ "초 : ",end=" ")            
            time.sleep(int(interval))
        intx +=1
        
    for thread in threads:
        thread.join()

