import sys

import socket
import time
import os
import requests , json
from random import randrange
import random
import pyautogui
import pydirectinput
import datetime 
import glob

from PIL import ImageGrab, Image

import threading

import ftplib   #pip install pyftpdlib

from pytesseract import Output
import pytesseract
import cv2
import numpy as np
import pyocr
import pyocr.builders

import pathlib
 


#import subprocess

#모바일에 키보드입력(한글제외)모듈
from pynput.keyboard import Key, Controller

#키누르기 감지
import msvcrt
import keyboard
import pyperclip

#윈도우창 제어
from pywinauto.application import Application
from win32 import win32gui


#import mouse

import subprocess

import ctypes
ctypes.windll.kernel32.SetConsoleTitleW("StartBee")

import re


def getWindowList():
    def callback(hwnd, hwnd_list: list):
        title = win32gui.GetWindowText(hwnd)
        if win32gui.IsWindowEnabled(hwnd) and win32gui.IsWindowVisible(hwnd) and title:
            hwnd_list.append((title, hwnd))
        return True
    output = []
    win32gui.EnumWindows(callback, output)
    return output

def app(serial):
    try:        
        os.system("adb -s "+str(serial)+" shell input keyevent KEYCODE_APP_SWITCH > nul")
    except:
        pass


def error_check(number,serial,a):
    if a:
        pass
    else:      
        try:
            #os.system("adb -s "+str(serial)+" shell uiautomator dump > "+str(number)+".xml")
            command = ["adb", "-s", str(serial), "shell", "uiautomator", "dump"]#, ">", str(number) + ".xml"]
            x_uiauto = subprocess.run(command,stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            x_ui_error_output = x_uiauto.stderr.decode('utf-8')
            x_ui_error_outputs = x_uiauto.stdout.decode('utf-8')
            if x_ui_error_output:
                print(str(number)+"번 uiautomator 에러"+str(x_ui_error_output) +" - " +str(x_ui_error_outputs))
                uierror = requests.get("http://61.109.34.245/blue/?mode=uilog&msg=get_xpath_xy&com="+com_name+"&usr="+str(number)).json()
                if uierror['reboot'] == "on":
                    print(str(number)+"번 재부팅합니다")
                    os.system("adb -s "+str(serial)+" reboot ")
                    time.sleep(120)
                    return False                
        except Exception as e:
            print("####### uiautomator 에러 "+str(number)+"번 ")
            print(e)
            pass
        time.sleep(1)
        if os.path.isfile("C:/xyz/s/"+str(number)+"--"+str(serial)+".xml"):
            os.remove("C:/xyz/s/"+str(number)+"--"+str(serial)+".xml")
        try:
            #os.system("adb -s "+str(serial)+" pull /sdcard/window_dump.xml C:/xyz/s/"+str(number)+"--"+str(serial)+".xml > nul")
            command = ["adb", "-s", str(serial), "pull", "/sdcard/window_dump.xml", "C:/xyz/s/" + str(number) + "--" + str(serial) + ".xml"]
            subprocess.run(command,stdout=subprocess.DEVNULL)            
        except Exception as e:
            print("####### winndow_dump 에러 "+str(number)+"번 ")
            print(e)
            pass
                    
        time.sleep(1)        
    time.sleep(2)    
    with open('C:/xyz/s/'+str(number)+"--"+str(serial)+'.xml', 'r',encoding='utf-8') as f:
        xml_str = f.read()  

    ################ 에러검사
    id_reg = r'<node[^>]+text="(?:계정에 로그인하는 동안 문제가 발생했습니다\.|채팅하려면 로그인)"[^>]+bounds="\[([0-9]+),([0-9]+)\]\[([0-9]+),([0-9]+)\]"[^>]*?>'
    net_reg = r'<node[^>]+text="(?:인터넷에 연결되지 않음|인터넷 연결 없음|사이트에 연결할 수 없음|VPN이 풀린거 같습니다\.|다시 시도|[^"]+다시 시도)"[^>]+bounds="\[([0-9]+),([0-9]+)\]\[([0-9]+),([0-9]+)\]"[^>]*?>'
    iderror = re.findall(id_reg,xml_str)
    neterror = re.findall(net_reg,xml_str)
    if iderror:
        #print(iderror)
        return "iderror"
    elif neterror:
        return "neterror"
    else:
        return False


#uiautomator 사용해서 좌표 얻어오기 
def get_xpath_xy(q,t,a,b,number,serial):#검색단어/타입/안가져오기/클릭안하11111기/번호/시리얼.
    if a:
        pass
    else:
        try:
            #os.system("adb -s "+str(serial)+" shell uiautomator dump > "+str(number)+".xml")
            command = ["adb", "-s", str(serial), "shell", "uiautomator", "dump"]#, ">", str(number) + ".xml"]
            x_uiauto = subprocess.run(command,stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            x_ui_error_output = x_uiauto.stderr.decode('utf-8')
            x_ui_error_outputs = x_uiauto.stdout.decode('utf-8')
            if x_ui_error_output:
                print(str(number)+"번 uiautomator 에러"+str(x_ui_error_output) +" - " +str(x_ui_error_outputs))
                uierror = requests.get("http://61.109.34.245/blue/?mode=uilog&msg=get_xpath_xy&com="+com_name+"&usr="+str(number)).json()
                if uierror['reboot'] == "on":
                    print(str(number)+"번 재부팅합니다")
                    os.system("adb -s "+str(serial)+" reboot ")
                    time.sleep(120)
                    return False                
        except Exception as e:
            print("####### uiautomator 에러 "+str(number)+"번 ")
            print(e)
            pass
        
        time.sleep(0.5)
        if os.path.isfile("C:/xyz/s/"+str(number)+"--"+str(serial)+".xml"):
            os.remove("C:/xyz/s/"+str(number)+"--"+str(serial)+".xml")
        #os.system("adb -s "+str(serial)+" pull /sdcard/window_dump.xml C:/xyz/s/"+str(number)+"--"+str(serial)+".xml > nul")
        command = ["adb", "-s", str(serial), "pull", "/sdcard/window_dump.xml", "C:/xyz/s/" + str(number) + "--" + str(serial) + ".xml"]
        subprocess.run(command,stdout=subprocess.DEVNULL)
    time.sleep(0.5)    
    with open('C:/xyz/s/'+str(number)+"--"+str(serial)+'.xml', 'r',encoding='utf-8') as f:
        xml_str = f.read()     
    
    pattern_str = ''        
    if t == "desc":
        pattern_str =  'content-desc="'+str(q)
    elif t == "like":#글자가 들어있다면
        pattern_str =  'content-desc="[^"]*'+str(q)+'[^"]*'
    elif t == "likes":#좋아요
        pattern_str =  'content-desc="나 외에 사용자[^"]*좋아함'
    elif t == "subscribe":#구독 
        pattern_str =  'content-desc="[^"]*구독합니다\.'        
    elif t == "title":
        pattern_str =  'content-desc="[^-]+'+str(q)+'[^-]+[^"]*'        
    elif t == "text":
        if q == "모두 닫기":
            pattern_str = 'text="(?:모두 닫기|모두 지우기)'
        else:
            pattern_str =  'text="'+str(q)
            
    elif t == "setmsg":
        rsetmsg = "|".join(q)       
        pattern_str = '(?:content-desc|text)="[^"]*\.\s[^"]*('+str(rsetmsg)+')[^"]*'
        
    elif t == "msgsend":
        #pattern_str = 'text="[^"]*채팅하기'   # 2023 08 08
        #pattern_str = 'text="채팅...'
        pattern_str = 'text="(?:채팅...|[^"]*채팅하기)'   # 2023 08 20
        
    elif t == "id":
        pattern_str = 'resource-id="'+str(q)
    elif t == "ads":
        pattern_str = 'content-desc="[^"]*'+str(q)+'[^"]*(http[s]?[^\s]+)[^"]*'
    elif t == "adb":
        pattern_str = 'text="ADBKeyBoard[^"]*'       
    else:
        return False
    
    pattern = r'<node[^>]+'+str(pattern_str)+'"[^>]+bounds="\[([0-9]+),([0-9]+)\]\[([0-9]+),([0-9]+)\]"[^>]*?>'
    
    elements = re.findall(pattern, xml_str)
    

    if elements:        
        if t == "ads" : #광고는 주소와 좌표값을 돌려준다.
            return elements
            #target_url = str(elements[0][0])
            #tmp_x = int((int(elements[0][1])+int(elements[0][3])) / 2 )
            #tmp_y = int((int(elements[0][2])+int(elements[0][4])) / 2 )
            #return target_url, tmp_x , tmp_y
        
        elif t == "adb":
            return True
        
        elif t == "setmsg":           
            return elements[-1]
        
        elif t =="msgsend":
            tmp_x = int((int(elements[0][0])+int(elements[0][2])) / 2 )
            tmp_y = int((int(elements[0][1])+int(elements[0][3])) / 2 )
            return tmp_x , tmp_y           
        else:
            tmp_x = int((int(elements[0][0])+int(elements[0][2])) / 2 )
            tmp_y = int((int(elements[0][1])+int(elements[0][3])) / 2 )
            #print(tmp_x,tmp_y)
            if tmp_x and tmp_y:       
                if b:
                    pass
                else:
                    os.system("adb -s "+str(serial)+" shell input tap "+str(tmp_x)+" "+str(tmp_y))
                return True
            else:
                return False
    else:
        
        return False


def get_cxpath_xy(q,t,a,b,number,serial,nick):#검색단어/타입/안가져오기/클릭안하11111기/번호/시리얼.
    if a:
        pass
    else:
        try:
            #os.system("adb -s "+str(serial)+" shell uiautomator dump > "+str(number)+".xml")
            command = ["adb", "-s", str(serial), "shell", "uiautomator", "dump"]#, ">", str(number) + ".xml"]
            x_uiauto = subprocess.run(command,stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            x_ui_error_output = x_uiauto.stderr.decode('utf-8')
            x_ui_error_outputs = x_uiauto.stdout.decode('utf-8')
            if x_ui_error_output:
                print(str(number)+"번 uiautomator 에러"+str(x_ui_error_output) +" - " +str(x_ui_error_outputs))
                uierror = requests.get("http://61.109.34.245/blue/?mode=uilog&msg=get_xpath_xy&com="+com_name+"&usr="+str(number)).json()
                if uierror['reboot'] == "on":
                    print(str(number)+"번 재부팅합니다")
                    os.system("adb -s "+str(serial)+" reboot ")
                    time.sleep(120)
                    return False                
        except Exception as e:
            print("####### uiautomator 에러 "+str(number)+"번 ")
            print(e)
            pass
        
        time.sleep(0.5)
        if os.path.isfile("C:/xyz/s/"+str(number)+"--"+str(serial)+".xml"):
            os.remove("C:/xyz/s/"+str(number)+"--"+str(serial)+".xml")
        #os.system("adb -s "+str(serial)+" pull /sdcard/window_dump.xml C:/xyz/s/"+str(number)+"--"+str(serial)+".xml > nul")
        command = ["adb", "-s", str(serial), "pull", "/sdcard/window_dump.xml", "C:/xyz/s/" + str(number) + "--" + str(serial) + ".xml"]
        subprocess.run(command,stdout=subprocess.DEVNULL)
    time.sleep(0.5)    
    with open('C:/xyz/s/'+str(number)+"--"+str(serial)+'.xml', 'r',encoding='utf-8') as f:
        xml_str = f.read()     
    
    pattern_str = ''        
    if t == "setmsg":
        rsetmsg = "|".join(q)
        if nick:
            pattern_str = '(?:content-desc|text)="[^"]*'+str(nick)+'[^\.]*\.\s[^"]*('+str(rsetmsg)+')[^"]*'
        else:
            pattern_str = '(?:content-desc|text)="[^"]*\.\s[^"]*('+str(rsetmsg)+')[^"]*'
        
    elif t == "msgsend":
        #pattern_str = 'text="[^"]*채팅하기'
        #pattern_str = 'text="채팅...'
        pattern_str = 'text="(?:채팅...|[^"]*채팅하기)'   # 2023 08 20
        
    elif t == "msgchange":#글자가 들어있다면
        pattern_str =  'content-desc="[^"]*'+str(q)+'[^"]*'
                
    else:
        return False
    
    pattern = r'<node[^>]+'+str(pattern_str)+'"[^>]+bounds="\[([0-9]+),([0-9]+)\]\[([0-9]+),([0-9]+)\]"[^>]*?>'
    #print(pattern)
    
    elements = re.findall(pattern, xml_str)
    #print(elements)

    if elements:        
        if t == "setmsg":           
            return elements[-1]
        
        elif t =="msgsend":
            tmp_x = int((int(elements[0][0])+int(elements[0][2])) / 2 )
            tmp_y = int((int(elements[0][1])+int(elements[0][3])) / 2 )
            return tmp_x , tmp_y           
        else:
            tmp_x = int((int(elements[0][0])+int(elements[0][2])) / 2 )
            tmp_y = int((int(elements[0][1])+int(elements[0][3])) / 2 )
            #print(tmp_x,tmp_y)
            if tmp_x and tmp_y:       
                if b:
                    pass
                else:
                    os.system("adb -s "+str(serial)+" shell input tap "+str(tmp_x)+" "+str(tmp_y))
                return True
            else:
                return False
    else:
        
        return False

    
def realtime_message(number,serial,com_name,usr,setmsg,tomsg,s_type,controle,c_q,nick):
    
    #if not setmsg and not tomsg:
    #    return False    
    
    chat_x = ''
    chat_y = ''
    idnet = ''
    #if get_xpath_xy("실시간 채팅","desc","","",number,serial):#채팅창 열기
    #    time.sleep(6)
    try:
        
        if get_xpath_xy("채팅하려면 로그인 ","text","","",number,serial):#계정 빠졌을 경우
            return  "iderror"

        #if get_xpath_xy("다시 시도","text","no","",number,serial):# 연결 끊기면 다시 시도 
        #    return "neterror"   

        #건너띌 필요가 없긴함
        # 2023-08- 07

        #if get_xpath_xy("채팅 작업","desc","","",number,serial):#설문조사옵션        
        #    time.sleep(3)
        #    if get_xpath_xy("설문조사 닫기","text","","",number,serial):#설문닫기
        #        print(str(number) + "번 설문조사 닫기")    
        #        pass
        #    #elif get_xpath_xy("고정된 메세지 닫기","text","no","",number,serial):#설문닫기 #2023-08-07 
        #    #    pass
            
    except Exception as e:
        print(str(number) + "번 채팅 로그인 /등등  설문 고정 닫기 관련 에러")   
        print(e)
        
        #if com_name == "k001":
        #    print(e)            
  
    #채팅창 변경되어져서 열리는 경우 2023.07.02
    try:
        if get_cxpath_xy("채팅하기","msgchange","","",number,serial,nick):
            time.sleep(10)
    except Exception as e:
        print(str(number) + "번 채팅하기 화면 관련해서 관련 에러")         
        print(e)
        
        #if com_name == "k001":
        #    print(e)
    
    chat_x, chat_y = get_xpath_xy("채팅하기","msgsend","","",number,serial) # ~채팅하기 에서 채팅하기로 변경 20230808
    
    if chat_x and chat_y:
  
        print(str(number) + "번 채팅 모듈 대기")            

        if controle == "master":
            #chat_send(serial,"하이",chat_x,chat_y,number)
            print(str(number)+" 채팅 마스터 입장")
        elif controle == "slave":
            print(str(number)+" 채팅 슬레이브 입장")
            
    while True:
        try:
            sendmsg = ''
            h_y = ''
            chatset = []
            sendmsg_arr = []
            sm = ''
            if controle == "master":
                if com_name == "k001":
                    print (str(number)+"번 "+str(sendmsg)+" 채팅감지 0")
                try:
                    sendmsg_arr = get_cxpath_xy(setmsg,"setmsg","","no",number,serial,nick)#채팅감지                
                    if sendmsg_arr:
                        if com_name == "k001":
                            print (str(number)+"번 "+str(sendmsg)+" 채팅감지1 ")
                        sendmsg = sendmsg_arr[0]
                        h_y = int(sendmsg_arr[4])
                        
                        if sendmsg and (int(s_type) - int(h_y) < 600) :#int(h_y)*2 > int(s_type)*1 and 
                            now = time.localtime()
                            print (str(number)+"번 "+str(sendmsg)+" 채팅감지 "+"%04d/%02d/%02d %02d:%02d:%02d" % (now.tm_year, now.tm_mon, now.tm_mday, now.tm_hour, now.tm_min, now.tm_sec))
                            if com_name == "k001":
                                print (str(number)+"번 감지 문장 : "+str(sendmsg)+" 높이 : "+str(h_y)+" 기기 높이 "+str(s_type))
                            sm = sendmsg
                        else:
                            sm = ''
                except Excetion as e:
                    print(e)
                    pass
                
                chatset =  requests.get("https://www.pwa.pe.kr/chat/?mode=master&com="+com_name+"&usr="+str(number)+"&sm="+str(sm)+"&q="+str(c_q)).json()
                if com_name == "k001":
                    print(chatset)
                    
                if chatset:
                    setmsg = chatset['setmsg']
                    nick = chatset['nick']
                    controle = chatset['controle']
                    
                if sm:
                    time.sleep(30)
                else:
                    pass                                            
             
            elif controle == "slave":                
                chatset = requests.get("https://www.pwa.pe.kr/chat/?mode=slave&com="+com_name+"&usr="+str(number)+"&sm=&q="+str(c_q)).json()
                if com_name == "k001":
                    print(chatset)
                if chatset:
                     controle = chatset['controle']
                     
                if chatset['sendmsg']:
                    #print(chatset)
                    chat_send(serial,chatset['sendmsg'],chat_x,chat_y,number)                    
                    controle = chatset['controle']
                    time.sleep(40)
                else:
                    pass
                time.sleep(1)
                
                
            elif controle =="break":
                break


            
            if chatset['controle'] == "break":
                break
            elif chatset['controle'] == "pass":
                time.sleep(1)
                pass
            elif chatset['controle'] == '':
                break
            else:
                time.sleep(3)
                pass
            
        except Exception as e:
            #print(e)
            #print(str(number)+' real message error')
            time.sleep(2)
            break

        time.sleep(1)

    return "chatend" 


        
def chat_send(serial,t,chat_x,chat_y,number):
    os.system("adb -s "+str(serial)+" shell input tap "+str(chat_x)+" "+str(chat_y))
    #command = ["adb", "-s", str(serial), "shell", "input", "tap", str(chat_x), str(chat_y)]
    #subprocess.run(command, capture_output=True, text=True)
    #result = subprocess.run(command, capture_output=True, text=True)
    time.sleep(0.3)    
    os.system("adb -s "+str(serial)+" shell am broadcast -a ADB_INPUT_TEXT --es msg '"+str(t)+"' > nul")
    time.sleep(0.3)
    os.system("adb -s "+str(serial)+" shell input keyevent ENTER")
    time.sleep(0.5)
    try:
        if get_xpath_xy("ADBKeyBoard이(가) 중지됨","adb","","",number,serial):
            os.system("adb -s "+str(serial)+" shell input keyevent ENTER")
            time.sleep(1)
            os.system("adb -s "+str(serial)+" shell input keyevent ENTER")
            time.sleep(1)
            #os.system("adb -s "+str(serial)+" shell ime set com.sec.android.inputmethod/.SamsungKeypad")
    except:
        print(str(number)+' chat_send error')
        pass

      
    
                    
    
def chrome_close_tabs(number,serial,devicetype,s_type):
    global com_name
    global tab_close_arr

    try:
        get_xpath_xy("탭 전환 또는 닫기","desc","","",number,serial)
        time.sleep(1)
        get_xpath_xy("옵션 더보기","desc","","",number,serial)
        time.sleep(1)
        get_xpath_xy("탭 모두 닫기","desc","","",number,serial)
        time.sleep(1)
        get_xpath_xy("탭 모두 닫기","text","","",number,serial)
        time.sleep(3)
    except:
        pass
        

def esc(serial):
    try:
        os.system("adb -s "+str(serial)+" shell input keyevent KEYCODE_BACK ")
    except:
        pass
    
def home(serial):
    try:
        os.system("adb -s "+str(serial)+" shell input keyevent KEYCODE_HOME > nul")
    except:
        pass
    
def vpn_app(serial):
    os.system("adb -s "+str(serial)+" shell am start -n de.blinkt.openvpn/de.blinkt.openvpn.activities.MainActivity > nul")


def vpn_click(number,serial,s_type):
    time.sleep(2)
    try:
        get_xpath_xy("de.blinkt.openvpn:id/vpn_list_item_left","id","","",number,serial)
        time.sleep(1)
        get_xpath_xy("다시 연결","text","","",number,serial)
        time.sleep(10)   
        os.system("adb -s "+str(serial)+" shell input keyevent KEYCODE_BACK ")
    except:
        pass

def youtube_keyword(serial,text):
    try:
        os.system("adb -s "+str(serial)+" shell input text "+str(text))
        
    except:
        pass
 
def youtube_open(serial):
    os.system("adb -s "+str(serial)+" shell am start -a com.google.android.youtube.action.open.search -n com.google.android.youtube/com.google.android.apps.youtube.app.WatchWhileActivity > nul")

    
def youtube_home(serial):
    os.system("adb -s "+str(serial)+" shell am start -n com.google.android.youtube/com.google.android.apps.youtube.app.WatchWhileActivity > nul")
    
def chrome(serial):
    try:
        #os.system("adb -s "+str(serial)+" shell am start -n com.android.chrome/com.google.android.apps.chrome.Main > nul")
        command = ["adb", "-s", str(serial), "shell", "am", "start", "-n", "com.android.chrome/com.google.android.apps.chrome.Main"]
        subprocess.run(command,stdout=subprocess.DEVNULL)
    except Exception as e:
        pass     

def set_adbkeyboard(serial):
    os.system("adb -s "+str(serial)+" shell ime set com.android.adbkeyboard/.AdbIME > nul")
    
def ctrl_v_on(serial,number,text):
    global com_name
    global adb_more_enter    
    print(str(number)+"번 Ctrl + V : "+ str(text))    
    os.system("adb -s "+str(serial)+" shell am broadcast -a ADB_INPUT_TEXT --es msg '"+text+"' > nul")
    time.sleep(2)
    os.system("adb -s "+str(serial)+" shell input keyevent ENTER")    
    time.sleep(2)
    if get_xpath_xy("ADBKeyBoard이(가) 중지됨","adb","","",number,serial):
        os.system("adb -s "+str(serial)+" shell input keyevent ENTER")
        time.sleep(1)        
        os.system("adb -s "+str(serial)+" shell input keyevent ENTER")
        time.sleep(1)
    #os.system("adb -s "+str(serial)+" shell ime set com.sec.android.inputmethod/.SamsungKeypad")


def get_screenshot(number,serial):
    try:
        for i in range(0,3):
            os.system("adb -s "+str(serial)+" shell screencap -p /sdcard/Download/"+str(serial)+".png")
            time.sleep(4)
            os.system("adb -s "+str(serial)+" pull /sdcard/Download/"+str(serial)+".png C:/xyz/s/")               
            file_size = os.path.getsize("C:/xyz/s/"+str(serial)+".png")
            if file_size  > 10240:#10kb 이상이라면 검사한다.
                print(str(number)+"번 이미지 용량 "+str(file_size)+"pc 전송")
                return "on"
                pass
            else:
                if i>0 and i < 2 :
                    print(str(number)+"번 이미지 pc 재전송")
                elif i == 2:
                    print(str(number)+"번 이미지 pc 전송 에러")
                    return "error"
        
        pass

    except Exception as e:
        print(e)
        pass
    
    return "error"
    
    
def filter_realtime(serial,a,b,device_path,number):      
    try:        
        if get_xpath_xy("옵션 더보기","desc",'','',number,serial):
            pass
        else:
            return "off"

        time.sleep(5)          
        if get_xpath_xy("검색 필터","text",'','',number,serial): 
            pass
        else:
            if get_xpath_xy("필터","text",'no','',number,serial):
                pass
            else:
                return "off"            

        
        time.sleep(2)        
        if get_xpath_xy("실시간","text",'','',number,serial):            
            pass
        else:
            return "off"

        if a == "creat":
            time.sleep(1)            
            if get_xpath_xy("크리에이티브 커먼즈","text",'no','',number,serial): 
                pass
            else:
                return "off"
        if b == "1hour":
            time.sleep(1)
            if find_image(serial,number,"alltime",1,device_path) == "on":
                pass
            else:
                return "off"
            time.sleep(1)
            if find_image(serial,number,"1hour",1,device_path) == "on":
                pass
            else:
                return "off"
        
        time.sleep(1)       
        if get_xpath_xy("적용","text",'no','',number,serial):
            return "on"
        else:
            return "off"
        
        return "off"
    
    except Exception as e:
        print(e)
        print("filter_realtime error")
    return "off"


def find_com():
    while True:
        try:
            com_name = socket.gethostname()
            s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)	#3
            s.connect(('8.8.8.8', 0))
            local_ip = s.getsockname()[0]
            usr_path = os.getenv('USERPROFILE')
            desktop_path = os.getenv('USERPROFILE')+'/Desktop'
            ip = requests.get("http://wtfismyip.com/text").text.strip()
            print("com : "+com_name)
            print("local_ip : "+local_ip)            
            print("IP : "+ip) 
        except:
            print('cominfo_error retry 20min')
            
        if com_name and ip and usr_path and desktop_path:
            break
        time.sleep(1200)
        
    return com_name, ip, usr_path, desktop_path


def temp_del(usr_path):
    temp_path = usr_path+'/Pictures/'
    try:
        for file in os.scandir(temp_path):
            os.remove(file.path)                   
    except:
        pass    
    print("del screenshot folder")


def find_text(number,serial,q,opt):
    time.sleep(1)    
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
    msg="serverset";
    try:
        #imageT = ImageGrab.grab().crop((718,120,1248,1037))#add this value 718/127
        if get_screenshot(number,serial) == "on":
            pass
        else:
            if opt == "onlydata":
                return 0,0
            else:
                return ""
        imageT = cv2.imread('c:/xyz/s/'+str(serial)+'.png')
        if opt == "shop":            
            gray = cv2.cvtColor(imageT, cv2.COLOR_BGR2GRAY)
            rst,dst = cv2.threshold(gray, 210, 255, cv2.THRESH_BINARY)
            imageT = dst
            
        imageS = pytesseract.image_to_data(imageT, lang='kor+eng',  config='-c preserve_interword_spaces=1 --psm 4', output_type=Output.DICT)       
        split_q = list(q)
        #print(imageS)
        for i in range(0,len(imageS['text'])):
            text = imageS['text'][i]            
            conf = int(imageS['conf'][i])
            if text == q and imageS['top'][i] > 170 and conf > 50: #검색어 매칭단어를 쓰는 경우가 있어서 
                x = imageS['left'][i]
                y = imageS['top'][i]
                w = imageS['width'][i]
                h = imageS['height'][i]                
                f_x = x + int(w/2)
                f_y = y + int(h/2)               
                msg = "findtext"
                break
            
            split_cnt = 0
            
                  
            for o in range(0,len(split_q)):
                k = i + o                  
                split_SK = list(imageS['text'][k])
                
                for y in range(0,len(split_SK)):                        
                    if split_q[split_cnt] == split_SK[y]:
                        #print("찾는글자 "+ str(split_cnt) + " " + split_q[split_cnt])
                        #print(" 추출글자 " + str(y) + " " +split_SK[y])
                        #print("글자 "+str(split_cnt) +" 찾음")
                        split_cnt += 1                                                        
                    else:
                        split_cnt = 0
                        break

                    if split_cnt == len(split_q):
                        x = imageS['left'][k]
                        y = imageS['top'][k]
                        w = imageS['width'][k]
                        h = imageS['height'][k]                
                        f_x = x + int(w/2)
                        f_y = y + int(h/2)               
                        msg = "findtext"
                        break

                if msg == "findtext":                               
                    #print("find project = "+str(p))
                    f_x = f_x #+ 718
                    f_y = f_y #+ 120
                    if opt == "onlydata":
                        return f_x,f_y
                    else:
                        os.system("adb -s "+str(serial)+" shell input tap "+str(f_x)+" "+str(f_y))
                        #pyautogui.moveTo(f_x,f_y,1)
                        #pyautogui.click(f_x,f_y)
                    print(str(number)+"번 ##  "+str(f_x)+" f_y "+str(f_y)+" - "+str(x)+" - "+str(y)+" - "+str(w)+" - "+str(h))
                    break
            if msg == "findtext":
                break                

    except:        
        pass                
    return msg



    
def screenshot(serial,q,usr_path,search_nmb,device_path,number,s_type):
    time.sleep(1)
    f_x = ''
    f_y = ''
    msg = 'notinteract'
    error = ''
    p=0    
    #pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
    
    try:
        #temp_del(usr_path)


        error_scroll = 0
        
        for p in range(search_nmb):
            if p < 1:
                time.sleep(5)

            if get_xpath_xy(q,"title","","",number,serial):#매칭단어가있다면
                time.sleep(3) #영상 클릭후 창이 열릴때까지 기다리기
                msg = "viewstart"
                #for i in range(0,4):
                #    if get_xpath_xy("닫기","like","","",number,serial):#창 닫기가 있다면
                #        msg = "viewstart"
                #        break
                #    time.sleep(3)
                #    
                #if get_xpath_xy("광고 건너띄기","text","","",number,serial):#창 닫기가 있다면
                #    time.sleep(3)
                #
                if msg == "viewstart":
                    return msg
                
                #if get_xpath_xy(q,"like","","no",number,serial):#매칭단어가있다면
                #    msg = "viewstart"
                #    return msg
                    
                
                
            
            if msg != "viewstart" and search_nmb > 1:
                
                if str(s_type) == "1280":
                    os.system("adb -s "+str(serial)+" shell input swipe 300 900 324 250")
                    
                else:
                    dragrandom = 400 + randrange(0,120)
                    scroll_p = int(s_type) - int(dragrandom)
                    os.system("adb -s "+str(serial)+" shell input swipe 500 "+str(scroll_p)+" "+str(dragrandom)+" 500")
                    
                p=p+1

            time.sleep(2)
            

    except Exception as e:
        error = "notinteract"
        print(e)
        print(str(number)+"번 검색결과에서 클릭 error")
        
    if error:
        msg = error
        esc(serial)
        print(str(number)+"번 검색검증에러  " +str(q)+" 에서 " +msg)        
    return msg



def find_image(serial,number,f,t,device_path,opt=True):
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
    print(str(number)+"번 " + str(f)+" ### 이미지를 찾습니다 ###")
    #com_check = socket.gethostname()
    if opt:
        if get_screenshot(number,serial)=="on":
            pass
        else:
            return "off"
        
    time.sleep(2)
    imagefile_r = []
    try:
        s = int(t)
        
        if f == "s":#검색후 스크롤 단계에서 체크 .검색어/필터를 제대로 입력했는지.
            device_path = "C:/xyz/device/common"
            
        imagefile = device_path+"/"+f+".png"
        #print(imagefile)
        for i in range(0,5):            
            if i>0:
                imagefile_t = device_path+"/"+f+str(i)+".png"
            else:
                imagefile_t = device_path+"/"+f+".png"
                
            if os.path.isfile(imagefile_t):              
                imagefile_r.append(imagefile_t)                                   
        
                  
        imageY = cv2.imread('c:/xyz/s/'+str(serial)+'.png')

        #print(str(number)+"번 " + 'c:/xyz/s/'+str(serial)+'.png')

        for imagefile in imagefile_r:
           
            chk_img = cv2.imread(imagefile)
            h, w = chk_img.shape[:2]

            img_results = cv2.matchTemplate(imageY,chk_img,cv2.TM_CCORR_NORMED)
           
            centerX = 0
            centerY = 0
            minVal, maxVal, minLoc, maxLoc = cv2.minMaxLoc(img_results)
            print(str(number)+"번 이미지 "+str(imagefile)+" 매칭 최대값 " +str(maxVal))
            #매칭 최대값 조절부분 
            if maxVal > 0.97 :
                
                centerX = maxLoc[0] + w//2
                centerY = maxLoc[1] + h//2
              
                if (f == "3vdot" or f == "filter" ) and centerY < 600 :
                    
                    print(str(number)+"번 "+str(f)+" 이미지- 존재 "+str(centerX)+" "+str(centerY))
                    os.system("adb -s "+str(serial)+" shell input tap "+str(centerX)+" "+str(centerY))
                    return "on"
                
                elif (f == "s" ) and centerY > 1600:
                    print(str(number)+"번 "+str(f)+" 이미지- 존재 "+str(centerX)+" "+str(centerY))
                    return "on"
                
                elif (f != "3vdot" and f != "filter" ) and f and centerX and centerY:
                    print(str(number)+"번 "+str(f)+" 이미지= 존재 "+str(centerX)+" "+str(centerY))
                    os.system("adb -s "+str(serial)+" shell input tap "+str(centerX)+" "+str(centerY))
                    return "on"
                    
                else:
                    pass
            time.sleep(1)
        return "off"
    except Exception as e:
        print(e)
        return "off"

def naver_search():
    pyautogui.moveTo(823,296,0.5)
    pyautogui.click(823,296)      
def dna_scrolldown():
    pyautogui.moveTo(948,882,1)#912
    pyautogui.dragTo(930, 200, 1, button='left')
def dna_scrollup():
    pyautogui.moveTo(948,200,1)#912
    pyautogui.dragTo(930, 882, 1, button='left')   
def dna_backspace(a):
    for i in range(a):
        pyautogui.click(1172, 857)
        time.sleep(0.2)
        
def chrome_url_text(serial,itemurl):
    os.system("adb -s "+str(serial)+' shell am start -n com.android.chrome/com.google.android.apps.chrome.Main -d "'+str(itemurl)+'" > nul ') 


#스폰서 필터 
def filter_sponsor(q,turl):
    target_url = ''   
    try:            
        print(adslist)    
        for i in range(0,len(q)):
            if q[i] in turl:
                target_url = '' 
                pass
            else:
                target_url = turl 
                pass                           
    except:
        print("filter_sponsor_error")
        pass                
    return target_url

    
def abuseClick(number,serial,devicetype,com_name):
    adclick = ''
    try:
        #print("https://www.pwa.pe.kr/adclick/?mode=getadclick&com="+com_name+"&usr="+str(usr))
        adclick = requests.get("https://www.pwa.pe.kr/adclick/?mode=getadclick&com="+com_name+"&usr="+str(number)).json()
        #print(adclick)
        if adclick['gglkeyword']:
                        
            keyword = adclick['gglkeyword']
            mysite = adclick['mysite']
            home(serial)
            time.sleep(1)
            chrome(serial)
            time.sleep(2) 
            chrome_url_text(serial,"https://www.google.com/search?q="+str(keyword))
            time.sleep(6)
            s_x = ''
            s_y = ''

            adslist = get_xpath_xy("스폰서","ads","","",number,serial)
            if adslist:
                print(adslist)#                        

   
            #print(imageS)  
            for i in range(0,len(adslist)):
                target_url = filter_sponsor(mysite,adslist[i][0])
                if not target_url: #설정해둔 광고클릭안할 사이트 라면
                    continue                      
                
                print("광고클릭한다")                    
                adlog = requests.get("https://www.pwa.pe.kr/adclick/?mode=log&gglkeyword="+keyword+"&turl="+filterSponsor+"&com="+com_name+"&usr="+str(number))
                print(adlog)
                    
                if adlog['status'] == "on":
                    s_x = int((int(adslist[i][1])+int(adslist[i][3])) / 2 )
                    s_y = int((int(adslist[i][2])+int(adslist[i][4])) / 2 )
                        
                    os.system("adb -s "+str(serial)+" shell input tap "+str(s_x)+" "+str(s_y))
                    time.sleep(4)
                    dragrandom = 400 + randrange(0,120)
                    os.system("adb -s "+str(serial)+" shell input swipe 500 1300 "+str(dragrandom)+" 500") 
                    time.sleep(6)
                        
                    for i in range(1,3):
                        stayrandom = randrange(10,20)
                        time.sleep(stayrandom)
                        dragrandom = 300 + randrange(0,220)
                        dragrandom1 = 300 + randrange(0,220)
                        os.system("adb -s "+str(serial)+" shell input swipe 500 1320 "+str(dragrandom)+" "+str(dragrandom1))
                    return "adclick"
                else:
                    return "noclick"
                
    except Exception as e:
        print(e)
        print("error")
        return 
        

    
            
def job(number,viewers,target,reset,program,ver):
    global com_name
    global ip
    global usr_path
    global desktop_path
    global usblines

    s_type = ''
    
    like_click = ''
    scrap_click = ''

    idnet = ''
    chaton = 'off'
    setmsg = []
    tomsg = []
    
    
    try:
        #print(usblines)
        find_order = ""
        for usbline in usblines:
            order_device_ =  usbline.split(" ")
            find_order = order_device_[2].replace("\n","")
            if str(number) == find_order:
                serial = order_device_[0]
                devicetype = order_device_[1] 
                print("찾는 번호 "+str(number)+" txt 번호 "+str(find_order) + " serial "+str(serial)+" model "+str(devicetype))                                
                break
            
        if "@" in devicetype:
            print("postion 파일에서 "+str(number)+"줄 마지막에 @를 붙여 통과시키는 뷰어입니다")
            msg = 'seterror'
            raise        
    except Exception as e:
        print(e)
        print(str(number)+"번 서버 serial정보를 가져오지 못했습니다")
        time.sleep(14400)
        pass

    #해상도 정보를 서버로 전송시키기 위해서 
    try:
        wm_args = "adb -s "+str(serial)+" shell wm size"
        wm_args_ = wm_args.split(" ")
        wm_push = subprocess.check_output(wm_args_)
        wm_lines_ = str(wm_push).replace("b'","")
        wm_lines__ = wm_lines_.replace("\\r\\n'","")
        wm_lines = wm_lines__.replace("\\r\\n"," - ")
        s_type = wm_lines.split("x")[-1]
        
        #print(str(number)+"번 사이즈 값 "+str(s_type))
          
    except Exception as e:
        print(e)
        pass    
    try:
        os.system("adb -s "+str(serial)+" shell ime set com.android.adbkeyboard/.AdbIME > nul")
        if str(reset) == "1" :            
            home(serial)            
            print(str(number)+"번 초기화로 최근앱닫기")
            time.sleep(1)
            app(serial)
            time.sleep(2)            
            get_xpath_xy("모두 닫기","text","","",number,serial)#close_all(serial,devicetype,s_type)
            time.sleep(1)
        else:
            print(str(number)+"번 초기화 패스로 최근앱닫기통과")
    except Exception as e:
        print(e)
        pass
    
        
    print(str(number)+"번 모바일 할당 시작")

    
    while True:
        msg = 'critical'
        usr = ''
        adsclick_con= ''
        fidnet = ''
        idnet = ''
    
        try:
            fidnet = error_check(number,serial,"")
            if fidnet:                
                if com_name == "k001":
                    print(str(number)+"번 "+str(fidnet)+" 에러 상태입니다")
                pass
            
            server = requests.get("http://61.109.34.245/blue/?mode=project&adb=bee&com="+com_name+"&ip="+ip+"&target="+str(target)+"&usr="+str(number)+"&model="+str(devicetype)+"&size="+str(wm_lines)+"&serial="+str(serial)+"&idnet="+str(fidnet)).json()
            if com_name == "k001":
                print("?mode=project&adb=bee&com="+com_name+"&ip="+ip+"&target="+str(target)+"&usr="+str(number)+"&model="+str(devicetype)+"&size="+str(wm_lines)+"&serial="+str(serial)+"&idnet="+str(fidnet))
                print(server)
            
            c_q = ''
            if server['q']:
                c_q = server['q']

            
            if str(server['team']) == str(number): #서버 수정없이 할당된 것이 현재 모바일과 같은 숫자라면 
                usr = server['team']                
                if "@" in devicetype:
                    print("postion 파일에서 "+str(server['team'])+"줄 마지막에 @를 붙여 통과시키는 뷰어입니다")
                    msg = 'seterror'
                    raise
            else:
                print(" "+str(number)+"번에 할당된 뷰어가 없습니다")
                msg = 'servercheck'
                raise
                        
            if server['pass'] == 'pass' and server['team'] and server['qname']:
                #blue_team(int(server['team']))
                print(" ")
                print("# "+str(number)+"번 최종 시청시각  "+server['viewdatetime'])                
                print("# "+str(number)+"번 현재 시작시각 ",end=" ")
                print(datetime.datetime.now())
                if program != "2":
                    print(str(number)+"##  "+str(server['team'])+"번 "+server['qname']+" 시작 ")
                    pass
                elif program == "2":
                    print(str(number)+"##  "+str(server['team'])+"번 naver 시작 ")
                    pass
                             
                pass
                
            
            #device_path = desktop_path+"/chinaM/"+devicetype
            device_path = "c:/xyz/device/"+devicetype
            try:
                if not os.path.exists(device_path):
                    os.makedirs(device_path)
            except OSError:
                print("폴더 생성 에러 : ")
                
            #print(str(number)+" 모바일 모델 : "+str(devicetype))
            
            
            usim_pass = "off"
            if "k" in com_name or "K" in com_name :
                usim_pass = "off"
            else:
                usim_pass = "on"
            
            if server['vpncheck'] != usim_pass :

                print(str(number)+"번 vpn체크를 실행합니다. ")
                for v in range(0,2):
                    vpn_recheck = ''
                    
                    home(serial)
                    time.sleep(1)                                        
                    vpn_com = com_name.replace('k','1_')
                    vpn_com = vpn_com.replace('H','2_')


                    try:
                        chrome(serial)
                        time.sleep(1)
                        chrome_close_tabs(number,serial,devicetype,s_type)
                        
                    except Exception as e:
                        print(e)
                        print(str(number)+" 크롬 탭 에러 ")
                        pass                   

                    
                    
                    chrome_url_text(serial,'61.109.34.245/99/?_1='+vpn_com+'\&_2='+usr+'\&_99='+str(v))
                   
                    
                    time.sleep(3)
                    
                    
                    vpn = requests.get('http://61.109.34.245/99/?json=json&_1='+vpn_com+'&_2='+usr).json()




                
                    if vpn['vpn_ok'] == 'ok':
                        #vpn이 정상이어야지 시청하지 않을때 광고클릭으로 간다
                        adsclick_con = "ok"
                        break;
                    elif vpn['vpn_ok'] == 'nointernet':
                        print(str(number)+"번 인터넷 에러입니다")                    
                        vpn_recheck = 'nointernet'
                        if v == 1:
                            raise
                    else:
                        print(str(number)+"번 VPN 에러입니다")
                        vpn_recheck = 'vpn_error'
                        if v == 1:
                            raise                       


                    #vpn re_check
                    if vpn_recheck != "" and v == 0:
                        print(str(number)+"번 VPN 앱실행")                        
                        home(serial)
                        time.sleep(1)
                        try:
                            vpn_app(serial)
                        except:
                            pass

                        time.sleep(2)
                        vpn_click(number,serial,s_type)
                        print(str(number)+"번 VPN 클릭 10초 대기")
                        time.sleep(10)



                
            #뷰어 프로젝트 실행   할당 받는 경우에만 들어가는 부분                                          
            if server['pass'] == 'pass' and server['team'] and server['qname'] != 'naver' and server['qname'] and program != "2"  :

                try:
                    youtube_home(serial)
                except:
                    pass
                


                home(serial)
                print(str(number)+"번 최근앱닫기")
                time.sleep(1)
                app(serial)
                time.sleep(2)                
                get_xpath_xy("모두 닫기","text","","",number,serial)#close_all(serial,devicetype,s_type)
                time.sleep(1)                
                home(serial)                
                time.sleep(1)
                
                youtube_open(serial)
                #print("youtube")
                time.sleep(3)

                    
                get_xpath_xy("소형 플레리어 닫기","desc","","",number,serial)#소형 플레이어 닫기 
                get_xpath_xy("무⁠료⁠체⁠험⁠건⁠너⁠뛰⁠기","desc","no","",number,serial)#무료체험건너띄기 닫기


                try:
                    time.sleep(2)
                    ctrl_v_on(serial,number,str(server['keyword']))
                except Exception as e:
                    print(e)
                print(str(number)+"번 "+str(server['keyword'])+" - youtube keyword")

                #키워드 입력후 들어가는 게 느린 경우가 많아서 (5초로 늘림) 인터넷 상황따라 불규칙함 
                time.sleep(6)


                idnet = ""
                try:
                    idnet = error_check(number,serial,"")
                   
                    if idnet:
                        msg = str(idnet)
                        usim_pass = "xxx" #검색으로 가지 않고 서버로 계정오류/혹은 인터넷 보냄
                            
                except Exception as e:                    
                    print(e)
                    


                ####인터넷 오류인 경우에 검사부분 빼도될듯 
                if usim_pass == "xxx":                    
                    pass
                else:
                
    ###################채널키워드 
    ###################채널 키워드가 있다면 채널검색으로 들어간다. 
                    if server['mchannelkeyword']:
                        #첫번째채널로들어간다. (맨처음이니까 혹시라도)
                        print("채널 :"+server['qname'] +" 검색" ,end = " ")
                        if find_text(number,serial,server['qname'],"") == "findtext":
                            time.sleep(2)
                            #재생목록등 선택(회색이어서 글자추출안됨)
                            #if find_text(number,serial,server['mkeyword']) == "findtext":
                            #    time.sleep(2)
                            chkey = server['mchannelkeyword'].split(",")
                            cq = random.choice(chkey)
                            print(str(number)+"번 영상 검색 : "+cq,end = " " )                        
                            for o in range(0,2):
                                dna_scrolldown()
                                if find_text(number,serial,cq,"fq") == "findtext":                               
                                #msg =  screenshot(cq,usr_path,server['searchnmb'],device_path) #check_screenshot
                                    if server['adclick']:
                                        time.sleep(6)
                                        do_click(1152,259)
                                    msg = "viewstart"
                                    break
                            if msg !="viewstart":
                                
                                do_click(853,503)
                                msg = "viewstart"
                                
                        pass
                    
                    else:                   
                        if filter_realtime(serial,server['mode'],server['mode2'],device_path,number) == "on":
                            #실시간필터로 들어가는 경우(실시간시청) 
                            msg  = screenshot(serial,server['qname'],usr_path,server['searchnmb'],device_path,number,s_type) #check_screenshot                            
                            time.sleep(5)
                            #시청이지만 계정은 빠진 경우 
                            for i in range(0,4):
                                idnet = error_check(number,serial,"")
                                chat_close =  get_xpath_xy("닫기","like","","no",number,serial) 
                                
                                if not idnet and chat_close:
                                    if server['like'] == "on" or  server['scrap'] == "on":
                                        chaton = "off"                                         
                                        get_xpath_xy("닫기","like","no","",number,serial)
                                        
                                    elif server['chaton'] == "on" :
                                        chaton = "on"
                                        setmsg = server['setmsg']
                                        tomsg = server['tomsg']
                                        print(str(number)+"번 채팅모드 진입")
                                    else: #시청오프인경우에는 채팅창 닫아놓고 진행 
                                        get_xpath_xy("닫기","like","no","",number,serial)   
                                        chaton="off"
                                    break
                                elif idnet:                                
                                    break
                                time.sleep(2)
                

                            if get_xpath_xy("광고 건너띄기","text","","",number,serial):#창 닫기가 있다면
                                time.sleep(1)
                        
                            if msg == "viewstart":
                                chatfinish = ''
                                try:
                                    chatinfo = requests.get("http://61.109.34.245/blue/?mode=log&msg="+msg+"&com="+com_name+"&ip="+ip+"&usr="+str(number)+"&c_q="+str(c_q)+"&idnet="+str(idnet)).json()
                                    print("<< "+str(number)+"번 >>     /?mode=log&msg="+msg+"&com="+com_name+"&ip="+ip+"&usr="+str(number)+"&c_q="+str(c_q)+"&idnet="+str(idnet))                                    
                                    print(chatinfo)
                                    if chatinfo['chaton'] == "on":
                                        setmsg = chatinfo['setmsg']
                                        if chatinfo['controle']=="master":
                                            print(str(number)+"번 채팅 마스터  시작")
                                            chatfinish = realtime_message(number,serial,com_name,usr,setmsg,tomsg,s_type,"master",c_q,chatinfo['nick'] )
                                        elif chatinfo['controle']=="slave":
                                            print(str(number)+"번 채팅 슬레이브 시작")
                                            chatfinish = realtime_message(number,serial,com_name,usr,setmsg,tomsg,s_type,"slave",c_q,chatinfo['nick'])
                                except Exception as e:
                                    print(str(number)+"번 채팅 감지 에러")
                                    print(e)
                                    pass
                                
                                if chaton == "off" and (server['like'] == "on" or  server['scrap'] == "on"):   #계정 로그인 되어있고 채팅 닫기를 눌렀다면
                                    #get_xpath_xy("닫기","like","no","",number,serial)                                    
                                    
                                    if server['like'] == "on" :
                                        print(str(number)+"번 좋아요 대기")
                                        like_sleep = randrange(120,240)
                                        time.sleep(like_sleep)
                                        if get_xpath_xy("나 외에 사용자","likes","","",number,serial):#if find_image(serial,number,"like",1,device_path) == "on":
                                            print(str(number)+"번 좋아요 누름")
                                            pass
                                    if server['scrap'] == "on" :
                                        print(str(number)+"번 구독 대기")
                                        scrap_sleep = randrange(120,240)
                                        time.sleep(like_scrap)       
                                        if get_xpath_xy("구독합니다","subscribe","","",number,serial): #if find_image(serial,number,"scrap",1,device_path) == "on":
                                            print(str(number)+"번 구독  누름")
                                            pass  
                                
                        else:
                            home(serial)
                            msg = "failfindkeyword"
                            print(str(number)+"번 "+str(wm_lines)+" filter error")
                            

                    time.sleep(2)


                           
            if msg != "viewstart": #시청이 아니라면 에러를 전송한다. 
                requests.get("http://61.109.34.245/blue/?mode=log&msg="+msg+"&com="+com_name+"&ip="+ip+"&usr="+str(number)+"&c_q="+str(c_q))

            #print(str(number)+"번 " +msg + " ID:"+str(idnet)+" ("+str(server['team'])+")"+" "+ str(usr))



            
            
        except Exception as e:
            #print(str(number)+"번 할당없이 통과")
            pass
              
       
                        
        try:
            print("#################")      
            now = time.localtime()
            print (str(number)+"번 모바일 상태 "+"%04d/%02d/%02d %02d:%02d:%02d" % (now.tm_year, now.tm_mon, now.tm_mday, now.tm_hour, now.tm_min, now.tm_sec))
            if msg =='seterror' or msg == "iderror" or msg == "neterror":#설정 오류면 1시간 정지 인터넷 연결안된경우 같은
                print(str(number)+"번 "+str(msg)+"오류 30분대기 ...버전 ( "+str(ver)+" )")
                time.sleep(1800)
            else:
                if msg == "servercheck":
                    print(str(number)+"번 프로젝트 할당없이 다음 대기중...버전 ( "+str(ver)+" )")
                else:
                    print(str(number)+"번 "+str(msg)+ " netid "+str(idnet)+ " 다른 프로젝트 대기중...버전 ( "+str(ver)+" )")
        except Exception as e:
            if com_name == "k001":
                print(e)
            pass
                    
        try:
            if com_name == "k102" or com_name == "b106" :
                adsclick_con == "okxxxx"####두개는 광고클릭안한다.
                
            if msg == 'viewstart':
                if chatfinish == "chatend":
                    time.sleep(20)
                else:
                    time.sleep(server['viewstartsleep'])
                
                
            elif msg == 'critical':
                ####광고 클릭 부분 .
                if server['adclick'] == "tail" and adsclick_con == "ok" : ##head or tail
                    try:
                        if abuseClick(number,serial,devicetype,com_name) == "adclick":
                            print(str(number)+"번 광고 클릭성공")
                             
        
                        else:
                            print(str(number)+"번 광고 클릭없음")
                            pass
                    except:
                        print(str(number)+"번 광고 에러")
                        pass
                    
                time.sleep(server['criticalsleep'])
            else:
                if msg != 'servercheck' and server['adclick'] == "tail" and adsclick_con == "ok" : ##head or tail
                    try:
                        if abuseClick(number,serial,devicetype,com_name) == "adclick":
                            print(str(number)+"번 광고 클릭성공")                            
                            
                        else:
                            print(str(number)+"번 광고 클릭없음")
                            pass
                    except:
                        print(str(number)+"번 광고 에러")
                        pass
                  
                time.sleep(server['elsesleep'])
                
    
        except Exception as e:
            if com_name == "k001":
                print(e)
            time.sleep(300)
            pass
        
        time.sleep(30)       
       
        
    
def version_update(com_name,ip,desktp_path):
    APP_NAME = 'autobee'
    
    APP_VERSION = '1.0.0.0'
    try:
        print(APP_NAME + " " + APP_VERSION )
        
        #find current user desktop path
        desktop_path = os.getenv('USERPROFILE')+'/Desktop'
        if os.path.isfile(desktop_path+"/blue_.exe"):
            os.remove(desktop_path+"/blue_.exe")
            pass
         
        app_version =  requests.get("http:///61.109.34.245/blue/?version="+APP_VERSION+"&com="+com_name).json()
        print("/?version="+APP_VERSION+"&com="+com_name)
        #업데이트 명령 또는 새로고침 명령을 받으면 업데이트 실행.
        #새로고침은 동일버전에서 강제 적용을 위해서.
        if app_version['update'] == 'update' or app_version['update'] == 'refresh' :
            old_app_file = os.path.join(desktop_path,"blue.exe")
            back_app_file = os.path.join(desktop_path,"blue_.exe")
            os.rename(old_app_file,back_app_file)
            response = requests.get('http:///61.109.34.245/blue/blue.exe')
            with open(desktop_path+"/blue.exe", 'wb') as f:
                f.write(response.content)
            print('update finish ..',end=" ")
            time.sleep(2)
            os.system('shutdown -a')
            print('blue restart')
            time.sleep(2)
            os.system('shutdown -r -t 5')

        elif app_version['update']:
            print("blue.exe Now version " + app_version['update'])

        if os.path.isfile(desktop_path+"/blue.exe") and os.path.isfile(desktop_path+"/blue_.exe"):
            os.remove(desktop_path+"/blue_.exe")
            pass

    except:
        print("blue.exe 파일 업데이트 실패 ")

    #scroll
    #blue_team(1)
    #version_update()
    #blue_scroll(1,-410)




    try:
        f = open('serial.txt')
        lines = f.readlines()
    except Exception as e:
        print(e)
        print("바탕화면의 serial.txt 파일을 확인하세요 ")
        pass


def ftp_folder_upload(mtype="common"):    
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(mtype + " 타입 업데이트 start ")
    mtypepath = "C:/xyz/device/"+mtype
    
    pnglist = os.listdir(mtypepath)   
    ###############
    # 파일 업로드
    ###############
    try:
        # ftp 연결
        with ftplib.FTP() as ftp:
            ftp.connect(host=host,port=21)
            ftp.encoding = 'utf-8'
            s = ftp.login(user=user,passwd=passwd)

            ftp.cwd('/mobile_type/')  # 현재 폴더 이동
            try:
                ftp.mkd(mtype)
            except:
                pass
            ftp.cwd('/mobile_type/'+mtype)
            
            # 파일업로드
            for png in pnglist:               
                with open(file=mtypepath+"/"+png, mode='rb') as wf:
                    ftp.storbinary('STOR '+ png , wf)
            print(ftp.dir())
     
    except Exception as e:
        print(e)
        print(mtype + "  ftp error")
        
    print(mtype + " 타입 업데이트 finish " ,end = " ")
    print(datetime.datetime.now())
    print("-----------------------------------------------------")
    
def ftp_folder_download(mtype="common"):    
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(mtype + " 타입 다운로드 start ")
    mtypepath = "C:/xyz/device/"+mtype
    #pnglist = os.listdir(mtypepath)   
    ###############
    # 파일 다운로드
    ###############
    try:
        # ftp 연결
        with ftplib.FTP() as ftp:
            ftp.connect(host=host,port=21)
            ftp.encoding = 'utf-8'
            s = ftp.login(user=user,passwd=passwd)

            ftp.cwd('/mobile_type/')  # 현재 폴더 이동
            try:
                ftp.mkd(mtype)
            except:
                pass
            ftp.cwd('/mobile_type/'+mtype)
            pnglist = ftp.nlst()
            print(pnglist)
            # 파일업로드
            for png in pnglist:               
                with open(file=mtypepath+"/"+png, mode='wb') as wf:
                    ftp.retrbinary('RETR '+ png , wf.write)
            print(ftp.dir())
     
    except Exception as e:
        print(e)
        print(mtype + "  ftp error")
        
    print(mtype + " 타입 다운로드 finish " ,end = " ")
    print(datetime.datetime.now())
    print("-----------------------------------------------------")
    

    
def ftp_update():
    #g950u1
    #g955u1
    #g975u1
    #g9500    
    ftp_folder_upload('g950u1')
    ftp_folder_upload('g955u1')
    ftp_folder_upload('g975u1')
    ftp_folder_upload('g9500')

def ftp_download():
    ftp_folder_download('g950u1')
    ftp_folder_download('g955u1')
    ftp_folder_download('g975u1')
    ftp_folder_download('g9500')    

def ftp_pyfile_upload(ver):
    pver = ver.replace('.','')
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(" 파이썬 파일 "+str(pver)+" 업로드 시작")
    #mtypepath = os.getenv('USERPROFILE')+"/Desktop/chinaM/koreaM.py"
    mtypepath = "C:/xyz/start.py"
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
                ftp.storbinary('STOR start.py' , wf)
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
    mtypepath = "C:/xyz/start.py"
     
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
                ftp.retrbinary('RETR start.py' , wf.write)
            print(ftp.dir())
     
    except Exception as e:
        print(e)
        print("FTP 파이썬 파일 다운로드 에러")
        
    print( " 파이썬파일  다운로드  완 " ,end = " ")
    print(datetime.datetime.now())
    print("-----------------------------------------------------")




#test_screenshot()
#time.sleep(10000)



def job_restart(number,viewers,target,reset,program,ver):
    while True:
        try:
             job(number,viewers,target,reset,program,ver)

        except Exception as e:
            # Handle the exception or perform any necessary cleanup
            print(f"An error occurred: {e}")
            # Restart the thread
            time.sleep(600)
            thread = threading.Thread(target=job_restart, args=[number,viewers,0,reset,program,ver,])
            thread.start()
            
    
viewers = 20  
ver = 'XC1.0.0.0.94'
com_check = socket.gethostname()

print("")
print("################################")
print("AutoBee - "+str(ver) + " 버전 시작 ")
print("")


#1840 사용중앱이 포개져서 클릭 위치가 더 아래로 내려지는 형태로 세로로 나오는 경우
close_arr = ["k087","k088","k089","k092","k093","k094","k094","k095"]

#크롬탭에서 세로 시크릿창 가는 경우 
tab_close_arr =["k091","k096","k097","k098","k099","k100"]

#adb 키보드가 에러 나오는 경우
adb_more_enter = ["k087","k088","k089","k092","k093","k094","k094","k095","k102","b106"]

#adb_keyboard 에러나서 엔터 한번더 해야하는경우



common_path = "c:/xyz/device/common"

#try:
#    if not os.path.exists(common_path):
#        print("cv2 이미지 공통 폴더를 만듭니다")
#        os.makedirs(common_path)
#    if com_check == "k001":
#        ftp_folder_upload()
#    else:
#        ftp_folder_download()        
#except OSError:
#    print("공통 폴더 생성 에러 : ")


print(sys.argv)
auto_run = sys.argv[1] if len(sys.argv) > 1 else ''

                
while True:
    if auto_run == "auto":
        print(" 자동 재실행")
        program = ""
        program_msg = ""
        break
        
    else:
        program = input("유튜브는 1번 네이버는 2번 아니면 엔터 : ")    
        if program == "":
            program_msg = ""
            break
        elif program == "1":
            program_msg = "유튜브"
            break
        elif program == "2":
            program_msg = "네이버"
            break
print("")


while True:
    if auto_run == "auto":
        reset = "1"
        break
    else:
        reset = input("초기화를 원하지 않으면 1 초기화를 원하면 엔터  : ")    
        if reset == "":
            reset = "1"
            break
        elif reset == "1":
            reset = ""
            break

print("")

while True:
    if auto_run == "auto":
        spcviewer = ""
        break
    else:
        spcviewer = input("특정 뷰어로 실행하시려면 번호를 입력하세요 아니면 엔터 : ")
        if spcviewer == "":
            break
        elif int(spcviewer) >=1:
            break

print("")

while True:
    if auto_run == "auto":
        if com_check != "k001" :
            ftp_pyfile_download()
        break
    else:
        if com_check == "k001" :
            ftpsync = input("파이썬파일 서버로 업로드는 1번  아니면 엔터 : ")
        else:
            ftpsync = input("파이썬파일 다운로드는 1번)  아니면 엔터 : ")
            
        if ftpsync == "":
            break
        else:
            if com_check == "k001" and str(ftpsync) == "1":
                ftp_pyfile_upload(ver)
            else:
                ftp_pyfile_download()
                
            break
    
print("")

try:
    com_name , ip , usr_path , desktop_path = find_com()
    while True:
        try:
            
            #version_update(com_name,ip,desktp_path)
            try:
                f = open('c:/xyz/serial.txt')
                usblines = f.readlines()
                con_viewers = len(usblines)
                f.close()
            except Exception as e:
                print(e)
                print("폴더내 serial.txt 파일을 확인하세요 ")
                raise
            if con_viewers:
                viewers = con_viewers            
            requests.get("http://61.109.34.245/blue/?mode=set&ver="+ver+"&com="+com_name+"&ip="+ip+"&viewers="+str(con_viewers)+"&reset="+str(reset))            
            break
        except Exception as e:
            print(e)
            pass
        time.sleep(600)
except:
    print(e)


if spcviewer.isdigit():
    if int(spcviewer) >= 1:
        target = spcviewer
        print(str(target) + "번 뷰어로 직접 "+program_msg +"실행하기"+str(reset))
        job(spcviewer,viewers,target,reset,program,ver)
    else:
        
        
        if com_check == "k001" and sys.argv[1] == com_check:
            ftp_update()
        elif sys.argv[1] == com_check:
            ftp_download()
else:
    try:
        
        threads = []      
        cnt_viewers = con_viewers +1
        for number in range(1,cnt_viewers):
            threads.append(threading.Thread(target=job_restart, args=[number,viewers,0,reset,program,ver,]))
        intx = 0
        interval = 30
        distime = 5
        for thread in threads:
            thread.start()
            
            if intx>=0:
                interval += distime
                #print("시간간격 : "+str(interval)+ "초 : ",end=" ")            
                time.sleep(int(interval))
            intx +=1
            
        for thread in threads:
            thread.join()
    except Exception as e:
        print(e)
        if com_check == 'k001':
            os.system('pause')
        pass
   


