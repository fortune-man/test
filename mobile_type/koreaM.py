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



import ftplib   #pip install pyftpdlib

from pytesseract import Output
import pytesseract
import cv2
import numpy as np
import pyocr
import pyocr.builders


#import subprocess

#모바일에 키보드입력(한글제외)모듈
from pynput.keyboard import Key, Controller

#키누르기 감지
import msvcrt
import keyboard
import pyperclip




def mobile_info(a,desktop_path):
    try:
        f = open(desktop_path+'/chinaM/position.txt')
        lines = f.readlines()
    except Exception as e:
        print(e)
        print("바탕화면의 position.txt 파일을 확인하세요 ")
        pass
    r_a = a - 1
    position = lines[r_a].split("/")
    w1 = position[0]
    h1 = position[1]
    global chinaMtype
    chinaMtype = str(position[2]).strip()
    #print(position)
    return w1,h1,chinaMtype

def team_wh_cal(a,w,h,c,desktop_path): 
    time.sleep(3)
    w1,h1,chinaMtype = mobile_info(a,desktop_path)
    if int(w1) and int(h1):        
        pass
    else:
        print("position 파일의 좌표가 정확하지않습니다. 디폴트값으로 실행됩니다")
        if a < 11:
            w1 = (150*a) - 150 + w
            h1 = h
        elif a >= 11 and a <=20:
            w1 = (150*a)  - 1650 + w 
            h1 = 260 + h
        elif a >= 21 and a <=30:
            w1 = (150*a)  - 3150 + w 
            h1 = 520 + h
        elif a >= 31 and a <=40:
            w1 = (150*a)  - 4650 + w 
            h1 = 780 + h
                
    pyautogui.moveTo(int(w1),int(h1),2)
    time.sleep(0.5) 

    if c:
        for i in range(c):            
            pyautogui.click(int(w1),int(h1))            
            time.sleep(0.5)
    else:
        pass
    time.sleep(2)
    return "team click"


##team type
def blue_team(a,desktop_path):
    team_wh_cal(a,58,15,1,desktop_path)
    time.sleep(2)


##public type
def blue_ori():
    pyautogui.moveTo(1850,13,2)
    pyautogui.click(1850,13)

def chat_close():
    pyautogui.moveTo(1172,411,2)
    pyautogui.click(1172,411)

def do_click(w,h):
    pyautogui.moveTo(w,h,1)
    pyautogui.click(w,h)
    
def research1(i):
    if i==1:
        pyautogui.moveTo(954,808,1)
        pyautogui.click(954,808)
    elif i==2:
        pyautogui.moveTo(953,870,1)
        pyautogui.click(953,870)

def app():
    pyautogui.moveTo(825,1011,1)
    pyautogui.click(825,1011)

def close_all():
    pyautogui.moveTo(960,810,1)
    pyautogui.click(960,810)    

    
def esc(b):
    for i in range(b):
        pyautogui.click(1093,1011)
        time.sleep(0.5)

def home():
    pyautogui.moveTo(960,1012,1)
    pyautogui.click(960,1012)

def vpn_app():
    pyautogui.moveTo(1020,927,1)
    pyautogui.click(1020,927)

def vpn_click():
    global chinaMtype
    com_check = socket.gethostname()
    if chinaMtype == "g975u1":
        pyautogui.moveTo(844,234,1)
        pyautogui.click(844,234)
    else:
        pyautogui.moveTo(844,204,1)
        pyautogui.click(844,204)
    
def blue_scroll(b):
    pyautogui.moveTo(948,216,1)
    pyautogui.scroll(b)


def blue_drag():    
    pyautogui.moveTo(948,912,2)   
    pyautogui.dragTo(910, 138, 1, button='left')    

def like():
    pyautogui.moveTo(772,480,1)
    pyautogui.click(772,480)

def dislike():
    pyautogui.moveTo(858,475,1)
    pyautogui.click(858,475)
    
def chat():
    pyautogui.moveTo(942,476,1)
    pyautogui.click(942,476)
    
def scrap():
    pyautogui.moveTo(1167,558,1)
    pyautogui.click(1167,558)

def report():
    pyautogui.moveTo(1117,479,1)
    pyautogui.click(1117,479)

def youtube_open():
    pyautogui.moveTo(790,925,1)
    pyautogui.click(790,926)

def youtube_home():
    pyautogui.moveTo(766,952,1)
    pyautogui.click(766,952)    
    
def chrome():
    pyautogui.moveTo(898,923,1)
    pyautogui.click(898,923)

def ctrl_v_on(text):
    print("Ctrl + V : "+ str(text))
    pyperclip.copy(text)
    keyboard.press_and_release("ctrl",True,False)
    keyboard.press_and_release("v",True,True)
    keyboard.press_and_release("ctrl",False,True)
    
def wait_loading():
    try:
        pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
        i = 0
        for i in range(10):
            img = ImageGrab.grab().crop((718,145,1200,926)) #685
            #img.save('c:/Users/Administrator/keyword.png')
            imgtext = pytesseract.image_to_string(img,lang='kor', config='-c preserve_interword_spaces=1 --psm 4')
            if imgtext:            
                return "on"
            i = i + 1
            time.sleep(2)            
        return "off"
    except:
        return "error"
    
def viewerset(a,q,com_name):
    if a:
        blue_team(a)
        pyautogui.click(1110,926)
    else:
        for i in range(1,40):
            blue_team(i)
            pyautogui.click(1110,926)
            


def filter_realtime(a,b,chinaMtype_path):      
    try:
        #realtime filter
        time.sleep(3)
        if find_image("3vdot",3,chinaMtype_path):
            pass
        else:
            return "off"

        time.sleep(2)  
        if find_image("filter",4,chinaMtype_path):            
            pass
        else:
            return "off"

        
        time.sleep(1)
        if find_image("realtime",2,chinaMtype_path):
            pass
        else:
            return "off"

        if a == "creat":
            time.sleep(1)
            if find_image("creative",2,chinaMtype_path):
                pass
            else:
                return "off"
        if b == "1hour":
            time.sleep(1)
            if find_image("alltime",1,chinaMtype_path):
                pass
            else:
                return "off"
            time.sleep(0.5)
            if find_image("1hour",2,chinaMtype_path):
                pass
            else:return "off"
        
        time.sleep(1)
        if find_image("supply",1,chinaMtype_path):
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

def korean_jamo(q):
    CHOSUNG_LIST = ['ㄱ', 'ㄲ', 'ㄴ', 'ㄷ', 'ㄸ', 'ㄹ', 'ㅁ', 'ㅂ', 'ㅃ', 'ㅅ', 'ㅆ', 'ㅇ', 'ㅈ', 'ㅉ', 'ㅊ', 'ㅋ', 'ㅌ', 'ㅍ', 'ㅎ']
    # 중성 리스트. 00 ~ 20
    JUNGSUNG_LIST = ['ㅏ', 'ㅐ', 'ㅑ', 'ㅒ', 'ㅓ', 'ㅔ', 'ㅕ', 'ㅖ', 'ㅗ', 'ㅘ', 'ㅙ', 'ㅚ', 'ㅛ', 'ㅜ', 'ㅝ', 'ㅞ', 'ㅟ', 'ㅠ', 'ㅡ', 'ㅢ', 'ㅣ']
    # 종성 리스트. 00 ~ 27 + 1(1개 없음)
    JONGSUNG_LIST = [' ', 'ㄱ', 'ㄲ', 'ㄳ', 'ㄴ', 'ㄵ', 'ㄶ', 'ㄷ', 'ㄹ', 'ㄺ', 'ㄻ', 'ㄼ', 'ㄽ', 'ㄾ', 'ㄿ', 'ㅀ', 'ㅁ', 'ㅂ', 'ㅄ', 'ㅅ', 'ㅆ', 'ㅇ', 'ㅈ', 'ㅊ', 'ㅋ', 'ㅌ', 'ㅍ', 'ㅎ']
    r_lst = []
    for w in list(q.strip()):
        ## 영어인 경우 구분해서 작성함. 
        if '가'<=w<='힣':
            ## 588개 마다 초성이 바뀜. 
            ch1 = (ord(w) - ord('가'))//588
            ## 중성은 총 28가지 종류
            ch2 = ((ord(w) - ord('가')) - (588*ch1)) // 28
            ch3 = (ord(w) - ord('가')) - (588*ch1) - 28*ch2
            #r_lst.append([CHOSUNG_LIST[ch1], JUNGSUNG_LIST[ch2], JONGSUNG_LIST[ch3]])
            r_lst.append([ch1, ch2, ch3])
            
        else:
            r_lst.append([w])
    #print(r_lst)            
    return r_lst

def korean_keyboard(a,b):
    global chinaMtype
    com_check = socket.gethostname()
    if chinaMtype == "g955u" and com_check == "k002":
        w1 = [687,766,821,878,932,988,1044,1097,1155,1209]
        w2 = [735,793,847,905,961,1015,1072,1126,1182]
        h1 = 612
        h2 = 687
        h3 = 769
        h4 = 839
        h5 = 919
    else:        
        w1 = [747,791,842,890,938,985,1029,1075,1123,1170]
        w2 = [771,817,865,910,959,1006,1051,1101,1149]
        h1 = 682
        h2 = 745
        h3 = 808
        h4 = 873
        h5 = 938
    s = ''
    w = ''
    h = ''
    x = ''
    y = ''
    o = ''
    p = ''
    if b == 1:
        if a == 0:#ㄱ
            w = w1[3]
            h = h2
        elif a == 1:#ㄲ
            s = 1
            w = w2[3]
            h = h2            
        elif a == 2:#ㄴ
            w = w2[1]
            h = h3
        elif a == 3:#ㄷ
            w = w1[2]
            h = h2
        elif a == 4:#ㄸ
            s = 1
            w = w1[2]
            h = h2
        elif a == 5:#ㄹ
            w = w2[3]
            h = h3
        elif a == 6:#ㅁ
            w = w2[0]
            h = h3
        elif a == 7:#ㅂ
            w = w1[0]
            h = h2
        elif a == 8:#ㅃ
            s = 1
            w = w1[0]
            h = h2
        elif a == 9:#ㅅ
            w = w1[4]
            h = h2
        elif a == 10:#ㅆ
            s = 1
            w = w1[4]
            h = h2
        elif a == 11:#ㅇ
            w = w2[2]
            h = h3
        elif a == 12:#ㅈ
            w = w1[1]
            h = h2
        elif a == 13:#ㅉ
            s = 1
            w = w2[1]
            h = h3
        elif a == 14:#ㅊ
            w = w2[4]
            h = h4
        elif a == 15:#ㅋ
            w = w2[1]
            h = h4
        elif a == 16:#ㅌ
            w = w2[2]
            h = h4         
        elif a == 17:#ㅍ
            w = w2[4]
            h = h4        
        elif a == 18:#ㅎ
            w = w2[4]
            h = h3
            
    elif b==2:
        if a == 0:#ㅏ
            w = w2[7]
            h = h3
        if a == 1:#ㅐ
            w = w2[8]
            h = h2 
        if a == 2:#ㅑ
            w = w1[7]
            h = h2                           
        if a == 3:#ㅒ
            s = 1
            w = w1[8]
            h = h2 
        if a == 4:#ㅓ
            w = w2[6]
            h = h3 
        if a ==5 :#ㅔ
            w = w1[9]
            h = h2
        if a == 6:#ㅕ
            w = w1[6]
            h = h2 
        if a == 7:#ㅖ
            s = 1
            w = w1[9]
            h = h2 
        if a == 8:#ㅗ
            w = w2[5]
            h = h3
        if a == 9:#ㅘ
            x = 8
            y = 0
        if a == 10:#ㅙ
            x = 8
            y = 1                         
        if a == 11:#ㅚ
            x = 8
            y = 20 
        if a == 12:#ㅛ
            w = w1[5]
            h = h2                           
        if a == 13:#ㅜ
            w = w2[6]
            h = h4 
        if a == 14:#ㅝ
            x = 13
            y = 4
        if a == 15:#ㅞ
            x = 13
            y = 5 
        if a == 16:#ㅟ
            x = 13
            y = 20
        if a == 17:#ㅠ
            w = w2[5]
            h = h4 
        if a == 18:#ㅡ
            w = w2[7]
            h = h4 
        if a == 19:#ㅢ
            x = 18
            y = 20
        if a == 20:#ㅣ
            w = w2[8]
            h = h3 
            
   
    elif b == 3 :            
        if a == 0:#
            w = w2[5]
            h = h5
        if a == 1:#ㄱ
            o = 0                          
        if a == 2:#ㄲ
            o = 1            
        if a == 3:#ㄳ
            o = 1
            p = 9
        if a == 4:#ㄴ
            o = 2
        if a == 5:#ㄵ
            o = 2
            p = 12
        if a == 6:#ㄶ
            o = 2
            p = 18 
        if a == 7:#ㄷ
            o = 3 
        if a == 8:#ㄹ
            o = 5
        if a == 9:#ㄺ
            o = 5
            p = 0 
        if a == 10:#ㄻ
            o = 5
            p = 6
        if a == 11:#ㄼ
            o = 5
            p = 7
        if a == 12:#ㄽ
            o = 5
            p = 9 
        if a == 13:#ㄾ
            o = 5
            p = 16
        if a == 14:#ㄿ
            o = 5 
            p = 17
        if a == 15:#ㅀ
            o = 5
            p = 18
        if a == 16:#ㅁ
            o = 6
        if a == 17:#ㅂ
            o = 7            
        if a == 18:#ㅄ
            o = 7
            p = 9
        if a == 19:#ㅅ
            o = 9 
        if a == 20:#ㅆ
            o = 10             
        if a == 21:#ㅇ
            o = 11
        if a == 22:#ㅈ
            o = 12 
        if a == 23:#ㅊ
            o = 14
        if a == 24:#ㅋ
            o = 15
        if a == 25:#ㅌ
            o = 16
        if a == 26:#ㅍ
            o = 17 
        if a == 27:#ㅎ
            o = 18
    return (a,b,s,w,h,x,y,o,p)

def korean_macro(string):
    try:
        k_m = korean_jamo(string)
        for k_l in k_m:
            korean_keyboard_fx(k_l[0],1)
            korean_keyboard_fx(k_l[1],2)
            korean_keyboard_fx(k_l[2],3)
    except:
        print("macro error")
        
def korean_shift():
    pyautogui.moveTo(760,873,0.3)
    pyautogui.click(760,873)
    print("shift")
    
def korean_space():
    pyautogui.moveTo(959,935,0.3)
    pyautogui.click(959,935)
    print("space")
    
def korean_click(w,h):
    pyautogui.moveTo(w,h,0.2)
    pyautogui.click(w,h)
    #print(" 가로 좌표 : "+str(w)+" 세로 좌표 :"+str(h))


def korean_keyboard_fx(string,nmb):
    a,b,s,w,h,x,y,o,p = korean_keyboard(string,nmb)
    if b == 1:
        if s == 1:
            korean_shift()
        korean_click(w,h)
        
    elif b == 2:
        if s == 1:
            korean_shift()
            
        if w and h :
            korean_click(w,h)

        if x:
            a2,b2,s2,w2,h2,x2,y2,o2,p2 = korean_keyboard(x,2)
            korean_click(w2,h2)
            if y >= 0 :
                a22,b22,s22,w22,h22,x22,y22,o22,p22 = korean_keyboard(y,2)
                korean_click(w22,h22)

    elif b == 3:
        if a == 0:
            #korean_space()
            pass
        else:
            a3,b3,s3,w3,h3,x3,y3,o3,p3 = korean_keyboard(o,1)
            if s3 == 1:
                korean_shift()
            if w3 and h3:
                korean_click(w3,h3)
            if p3:
                a33,b33,s33,w33,h33,x33,y33,o33,p33 = korean_keyboard(p3,1)
                korean_click(w33,h33)
            #korean_space()

def english_shift():
    pyautogui.moveTo(760,873,0.3)
    pyautogui.click(760,873)
    print("shift")
    
def english_space():
    pyautogui.moveTo(959,935,0.3)
    pyautogui.click(959,935)
    print("space")
    
def english_click(w,h):
    pyautogui.moveTo(w,h,0.2)
    pyautogui.click(w,h)
    #print(" 가로 좌표 : "+str(w)+" 세로 좌표 :"+str(h))

def english_kerbord(q):
    # 소문자 리스트
    SMALL_LIST = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']
    # 대문자 리스트. 00 ~ 20
    CAPITAL_LIST = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']

    global chinaMtype
    com_check = socket.gethostname()
    if chinaMtype == "g955u" and com_check == "k002":
        w1 = [687,766,821,878,932,988,1044,1097,1155,1209]
        w2 = [735,793,847,905,961,1015,1072,1126,1182]
        h1 = 612
        h2 = 687
        h3 = 769
        h4 = 839
        h5 = 919
    else:        
        w1 = [741,791,842,890,938,985,1029,1075,1123,1170]
        w2 = [765,814,861,910,959,1006,1056,1104]
        h1 = 671
        h2 = 734
        h3 = 808
        h4 = 873
        h5 = 938
        
    for a in list(q):#.strip()
        if a == "a":
            w = w2[0]
            h = h3
        elif a == "b":
            w = w2[5]
            h = h4 
        elif a == "c":
            w = w2[3]
            h = h4         
        elif a == "d":
            w = w2[2]
            h = h3
        elif a == "e":
            w = w1[2]
            h = h2
        elif a == "f":
            w = w2[3]
            h = h3
        elif a == "g":
            w = w2[4]
            h = h3             
        elif a == "h":
            w = w2[5]
            h = h3            
        elif a == "i":
            w = w1[7]
            h = h2    
        elif a == "j":
            w = w2[6]
            h = h3 
        elif a == "k":
            w = w2[7]
            h = h3
        elif a == "l":
            w = w2[8]
            h = h3 
        elif a == "m":
            w = w2[7]
            h = h4           
        elif a == "n":
            w = w2[6]
            h = h4 
        elif a == "o":
            w = w1[8]
            h = h2 
        elif a == "p":
            w = w1[9]
            h = h2
        elif a == "q":
            w = w1[0]
            h = h2
        elif a == "r":
            w = w1[3]
            h = h2
        elif a == "s":
            w = w2[1]
            h = h3
        elif a == "t":
            w = w1[4]
            h = h2
        elif a == "u":
            w = w1[6]
            h = h2 
        elif a == "v":
            w = w2[4]
            h = h4   
        elif a == "w":
            w = w1[1]
            h = h2
        elif a == "x":
            w = w2[2]
            h = h4   
        elif a == "y":
            w = w1[5]
            h = h2    
        elif a == "z":
            w = w2[1]
            h = h4
        elif a == ".":
            w = w2[6]
            h = h5
            
        english_click(w,h)
        
def english_macro(string):
    try:
        english_kerbord(string)
    except Exception as e:
        print(e)
        print("english macro error")




def check_infos(mode):
    try:
        pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'           
        if mode == "like": 
            #772 492             //    860 483
            # 255 white  0 black
            pyautogui.moveTo(772,492,1)
            pos = pyautogui.position()
            img = ImageGrab.grab()
            color = img.getpixel(pos)
            if color[0] < 3:
                return "off"
            else:
                return "on"
        elif mode == "dislike":
            pyautogui.moveTo(860,483,1)
            pos = pyautogui.position()
            img = ImageGrab.grab()
            color = img.getpixel(pos)
            if color[0] < 3:
                return "off"
            else:
                return "on"        
        elif mode == "scrap":
            img = ImageGrab.grab().crop((1070,550,1195,585)) #685
            #img.save('c:/Users/Administrator/111.png')
            imgtext = pytesseract.image_to_string(img,lang='kor', config='-c preserve_interword_spaces=1 --psm 4')
            if "구독중" in imgtext:
                return "off"
            else:
                return "on"
        elif mode == "chat":
            img = ImageGrab.grab().crop((720,363,888,440)) #685
            #img.save('c:/Users/Administrator/333.png')
            imgtext = pytesseract.image_to_string(img,lang='kor', config='-c preserve_interword_spaces=1 --psm 4')
            #print(imgtext)
            if "실시간 채팅" in imgtext:
                return "on"
            else:
                return "off"            
    except:
        print("check_infos_error")
        pass
    
    return "error"


def find_text(q,opt):
    time.sleep(1)    
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
    msg="serverset";
    try:
        imageT = ImageGrab.grab().crop((718,120,1248,1037))#add this value 718/127
        if opt == "shop":
            imageT.save('c:/Users/Administrator/xx.png')
            imageY = cv2.imread('c:/Users/Administrator/xx.png')
            gray = cv2.cvtColor(imageY, cv2.COLOR_BGR2GRAY)
            rst,dst = cv2.threshold(gray, 210, 255, cv2.THRESH_BINARY)
            imageT = dst
            
        imageS = pytesseract.image_to_data(imageT, lang='kor+eng',  config='-c preserve_interword_spaces=1 --psm 4', output_type=Output.DICT)       
        split_q = list(q)
        #print(imageS)
        for i in range(0,len(imageS['text'])):
            text = imageS['text'][i]            
            conf = int(imageS['conf'][i])
            if text == q and conf > 50:
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
                    f_x = f_x + 718
                    f_y = f_y + 120
                    if opt == "onlydata":
                        return f_x,f_y
                    else:
                        pyautogui.moveTo(f_x,f_y,1)
                        pyautogui.click(f_x,f_y)
                    print("####  "+str(f_x)+" f_y "+str(f_y)+" - "+str(x)+" - "+str(y)+" - "+str(w)+" - "+str(h))
                    break
            if msg == "findtext":
                break                

    except:        
        pass                
    return msg



    
def screenshot(q,usr_path,search_nmb,chinaMtype_path):
    time.sleep(1)
    f_x = ''
    f_y = ''
    msg = ''
    error = ''
    p=0
    #clear folder
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
    
    try:
        #temp_del(usr_path)
        
        for p in range(search_nmb):
            if p < 1:
                time.sleep(3)
                print( " find "+q,end=" ")
            print(' + '+str(p),end=" .. ")
       
            #all size 718,23,1199,1037
            #target size 717/127 - 1199/924
            imageT = ImageGrab.grab().crop((718,120,1248,1037))#add this value 718/127
            

            
            imageT_text = pytesseract.image_to_string(imageT,lang='kor+eng', config='-c preserve_interword_spaces=1 --psm 4')
            #print(imageT_text)
            if imageT_text and error_check(imageT_text):
                error = error_check(imageT_text)
                break
            
            if q in imageT_text:
                print(" "+q+" in text")
                time.sleep(0.2)

                imageS = pytesseract.image_to_data(imageT, lang='kor+eng',  config='-c preserve_interword_spaces=1 --psm 4', output_type=Output.DICT)
                #print(imageS['text'])
                split_q = list(q)
                for i in range(0,len(imageS['text'])):
                    text = imageS['text'][i]
                    conf = int(imageS['conf'][i])
                    if text == q and conf > 50:
                        x = imageS['left'][i]
                        y = imageS['top'][i]
                        w = imageS['width'][i]
                        h = imageS['height'][i]                
                        f_x = x + int(w/2)
                        f_y = y + int(h/2)               
                        msg = "findviewstart"
                        break

                    split_cnt = 0
                    for o in range(0,len(split_q)):
                        k = i + o
                        if split_q[o] == imageS['text'][k]:
                            split_cnt += 1
                        else:
                            split_cnt = 0
                            break
                            
                    if split_cnt == len(split_q):
                        x = imageS['left'][i]
                        y = imageS['top'][i]
                        w = imageS['width'][i]
                        h = imageS['height'][i]                
                        f_x = x + int(w/2)
                        f_y = y + int(h/2)               
                        msg = "findviewstart"
                        break                        
                        
                    
            if msg == "findviewstart":
                               
                #print("find project = "+str(p))
                f_x = f_x + 718
                f_y = f_y + 120
                pyautogui.moveTo(f_x,f_y,1)
                pyautogui.click(f_x,f_y)
                print("####  "+str(f_x)+" f_y "+str(f_y)+" - "+str(x)+" - "+str(y)+" - "+str(w)+" - "+str(h))

                time.sleep(2)
                #chat_close()
                find_image("chat_close",4,chinaMtype_path)
                time.sleep(1)                 
                
                imageX = ImageGrab.grab().crop((718,120,1248,1037))#add this value 718/127
                imageX_text = pytesseract.image_to_string(imageX,lang='kor+eng', config='-c preserve_interword_spaces=1 --psm 4')
                #print(imageT_text)

                if q in imageX_text:
                    print("영상검증 : "+q+" 시청 시작")
                    msg = "viewstart"
                    time.sleep(0.3)               
                    break
                else:
                    error = "notinteract"
                    break
                            
            if msg != "viewstart" and search_nmb > 1:
                pyautogui.moveTo(948,912,1)#912
                dragrandom = 211 + randrange(0,10)
                pyautogui.dragTo(930, dragrandom, 1, button='left')    #138
                p=p+1
                time.sleep(0.5)

    except Exception as e:
        error = "notinteract"
        print(e)
        print("screenshot error")
        
    if error:
        msg = error
        esc(2)
        print(" " +msg)        
    return msg

def error_check(text):
    error = ''
    if text == '':
        error ='seterror'
    elif "오프라인 저장 동영상 없음" in text:
        error = "failfindkeyword"
    elif "많이 본 도움말 리소스" in text:
        error = "seterror"        
    elif "인터넷 연결" in text:
        error = "seterror"
    elif "오프라인 상태입니다" in text:
        error ="seterror"
    #elif "고객" in text:
    #    error ="seterror"        
    else:
        pass
    #print("error_check_"+error)
    return error


def find_image(f,t,chinaMtype_path):
    print(str(f)+" ### find image ###")
    com_check = socket.gethostname()
    imagefile_r = []
    try:
        s = int(t)    
        imagefile = chinaMtype_path+"/"+f+".png"
        for i in range(0,5):            
            if i>0:
                imagefile_t = chinaMtype_path+"/"+f+str(i)+".png"
            else:
                imagefile_t = chinaMtype_path+"/"+f+".png"            
            if os.path.isfile(imagefile_t):              
                imagefile_r.append(imagefile_t)               
        for i in range(s):
            
            if f == "moreshopping" or  f == "scrap" or f == "realtime" or f == "chat_close" or f=="search":
                for imagefile in imagefile_r:
                    find_img = pyautogui.locateOnScreen(imagefile,confidence=0.8)
                    if find_img:
                        break
                
                
            elif f == "narrowr" or f == "like"  or f == "3vdot":
                for imagefile in imagefile_r:
                    find_img = pyautogui.locateOnScreen(imagefile,confidence=0.7)
                    if find_img:
                        break
                    
                
            elif f == "naver":
                for imagefile in imagefile_r:
                    find_img = pyautogui.locateOnScreen(imagefile,confidence=0.6)
                    if find_img:
                        break                
                
            else:
                for imagefile in imagefile_r:
                    find_img = pyautogui.locateOnScreen(imagefile,confidence=0.9)
                    if find_img:
                        break
                
            if not find_img:
                if com_check=="k004" and ( f=="like" or f =="scrap") :
                    find_img = pyautogui.locateOnScreen(chinaMtype_path+"/"+f+"D.png",confidence=0.7)
                    
                if f == "yotube_home" or f == "premium":
                    find_img = pyautogui.locateOnScreen(chinaMtype_path+"/"+f+"D.png")
                        
            if not find_img:
                if f == "yotube_home" :
                    find_img = pyautogui.locateOnScreen(chinaMtype_path+"/"+f+"S.png")
                                          
            if find_img:
                print(str(f)+" 이미지 존재")
                #print(find_img)
                pyautogui.moveTo(find_img)
                pyautogui.click(find_img,duration=0.1)
                return "on"
                break
            else:
                pass
            time.sleep(1)
        return "off"
    except Exception as e:
        print(e)
        return "off"

def find_page(page,chinaMtype_path):

    pageN = ''
    # 1-5, 3-7, 5-9
    try:
        if int(page) <= 5:
            imagefile = chinaMtype_path+"/n1-5.png"
            find_img = pyautogui.locateOnScreen(imagefile,confidence=0.8)
        if find_img:
            pageimg = chinaMtype_path+"/npage"+str(page)+".png"
            pageN = pyautogui.locateOnScreen(pageimg,confidence=0.8)
        if pageN:
            print(str(page)+" 페이지 클릭")
            pyautogui.moveTo(pageN)
            pyautogui.click(pageN,duration=0.1)          
            return "on"
        return "off"
    except Exception as e:
        print(e)
        return "off"

    

def test_screenshot():
    time.sleep(3)
  
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
    
    try:
        imageT = ImageGrab.grab().crop((718,120,1248,1037))#add this value 718/127
       
        imageT_text = pytesseract.image_to_string(imageT,lang='kor+eng', config='-c preserve_interword_spaces=1 --psm 4')
        print(imageT_text)      
        imageS = pytesseract.image_to_data(imageT, lang='kor+eng',  config='-c preserve_interword_spaces=1 --psm 4', output_type=Output.DICT)
        print(imageS)
    except:
        print("error")
        


def dna_enter():
    global chinaMtype
    com_name = socket.gethostname()
    if (str(chinaMtype) == "g955u" and com_name =="k002") or (str(chinaMtype) == "g950u" and com_name =="k038") or (str(chinaMtype) == "g955u" and com_name =="k042") \
       or (str(chinaMtype) == "g950u" and com_name =="k043") or (str(chinaMtype) == "g950u1" and com_name =="k043") \
       or (str(chinaMtype) == "g955u" and com_name =="k044")\
       or (str(chinaMtype) == "g955u" and com_name =="k045")\
        :
        pyautogui.moveTo(1200,923,0.5)
        pyautogui.click(1200,923)
    else:
        pyautogui.moveTo(1190,934,0.5) #1167 / 923
        pyautogui.click(1190,934)
                    
 
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
        
def chrome_url_text(itemurl):
    global chinaMtype
    com_check = socket.gethostname()
    if chinaMtype == "g975u1":
        pyautogui.moveTo(933,114,1)
        pyautogui.click(933,114)
    else:
        pyautogui.moveTo(933,84,1)
        pyautogui.click(933,84)
    #kbControl = Controller()
    #kbControl.type(itemurl)
    ctrl_v_on(itemurl)
    time.sleep(0.4)
    dna_enter()

def dna_random(a,b):
    
    x = randrange(int(a),int(b))
    for i in range(x):
        s = randrange(5,10)
        time.sleep(s)
        
        pyautogui.moveTo(948,882,1)#912
        pdrto = randrange(400,700)
        pyautogui.dragTo(930, pdrto, 1, button='left')
        
        b = randrange(5,10)
        time.sleep(b)
        
def dna(team):
    com_name , ip , usr_path , desktop_path = find_com()
    w1,h1,chinaMtype = mobile_info(int(team),str(desktop_path))
    chinaMtype_path = desktop_path+"/chinaM/"+chinaMtype
    
    dna = requests.get("http://61.109.34.246/dnaserver/?mode=project&com="+com_name+"&usr="+str(team)).json()  
    if dna['project'] == "none":
        return "none"
    print(" 네이버슬롯 :"+dna['title'])    
    time.sleep(2)
    #find_image("chrome",2,chinaMtype_path)
    chrome()
    time.sleep(3)
    pyautogui.moveTo(933,84,1)
    pyautogui.click(933,84)
    time.sleep(3)
    #english_macro('naver.com')
    chrome_url_text('m.naver.com')    
    time.sleep(2)

    
    if int(dna['status']) == 1:        
        chrome_url_text(dna['itemurl'])
        time.sleep(5)
        dna_random(dna['random1'],dna['random2'])
        print("주소 상품 클릭")
        requests.get("http://61.109.34.246/dnaserver/?mode=log&msg=clickitem&mid="+dna['mid']+"&com="+com_name+"&usr="+str(team))
        return "click"
    else:
        pass

 
    #쇼핑더보기
    scroll_cnt = 1
    moreshopping_check = "find" #"none"


            
    time.sleep(2)
    #"https://m.search.naver.com/search.naver?sm=mtp_hty.top&where=m&query="+dna['keyword'] 메인에서 검색시
    
    if dna['project'] == 1:

        #메인에서 찍고 
        chrome_url_text("https://m.search.naver.com/search.naver?sm=mtp_hty.top&where=m&query="+dna['keyword'])
        time.sleep(2)
        if dna['page'] == 1:
            target_page = dna['page']
            if dna['rkeyword']:
                #연관키워드로 가는 것인데 키워드르 2번입력하는경우 
                page_url = "https://m.search.naver.com/search.naver?sm=mtb_hty.top&where=m&oquery="+dna['keyword']+"&tqi=hH7hFlprc%2B0ssjGOLj0ssssstWG-288475&query="+dna['rkeyword']        
            else:
                page_url = "https://msearch.shopping.naver.com/search/all?query="+dna['keyword']+"&bt=1&frm=MOSCPRO"                    
        else:
            target_page = dna['page']
            page_url = "https://msearch.shopping.naver.com/search/all?frm=NVSHPAG&origQuery="+dna['keyword']+"&pagingIndex="+dna['page']+"&pagingSize=40&productSet=total&query="+dna['rkeyword']+"&sort=rel&viewType=lst"
        print(str(dna['page'])+" 페이지에서 검색 ") 
    else:
        chrome_url_text('m.shopping.naver.com')    
        time.sleep(2)        
               
        if dna['page'] == 1:
            target_page = dna['page']
            if dna['rkeyword']:
                page_url = "https://msearch.shopping.naver.com/search/all?query="+dna['keyword']+"&frm=NVSHSRC&prevQuery="+dna['rkeyword']                  
            else:
                page_url = "https://msearch.shopping.naver.com/search/all?query="+dna['keyword']+"&frm=NVSHSRC&vertical=home&fs=true"        
        else:
            target_page = dna['page']
            page_url = "https://msearch.shopping.naver.com/search/all?frm=NVSHPAG&origQuery="+dna['keyword']+"&pagingIndex="+dna['page']+"&pagingSize=40&productSet=total&query="+dna['keyword']+"&sort=rel&viewType=lst"
        print(str(dna['page'])+" 페이지에서 검색 ")

    dna_scrollup()
    time.sleep(1)
    pyautogui.moveTo(933,84,1)
    pyautogui.click(933,84)
    time.sleep(1)
    chrome_url_text(page_url)        
    time.sleep(2)
    for i in range(0,30):
        #if shop_item(dna['fq']) == 'findviewstart':
        #    print("item find")
        #    time.sleep(30000)
        if dna['imagesearch'] == "image":
            try:
                findtargetitem = pyautogui.locateOnScreen(chinaMtype_path+"/"+dna['mid']+".png",confidence=0.9)
                pyautogui.moveTo(findtargetitem)
                pyautogui.click(findtargetitem,duration=0.1)
                print("검색 상품 이미지 매칭 클릭")
                requests.get("http://61.109.34.246/dnaserver/?mode=log&msg=clickitem&mid="+dna['mid']+"&com="+com_name+"&usr="+str(team))
                time.sleep(3)
                dna_random(dna['random1'],dna['random2'])
                return "click"
            except:
                findtargetitem = ''
                pass
            
        if find_text(str(dna['fq']),"shop") == "findtext":    
            print("검색 상품 fq 매칭  클릭")
            requests.get("http://61.109.34.246/dnaserver/?mode=log&msg=clickitem&mid="+dna['mid']+"&com="+com_name+"&usr="+str(team))
            time.sleep(2)
            dna_random(dna['random1'],dna['random2'])
            return "click"

        time.sleep(1)
        dna_scrolldown()
        
        #scroll_cnt = scroll_cnt + 1
    return "noclick"    
        
    
    #imageT = ImageGrab.grab().crop((718,120,1248,1037))
    #imageT_text = pytesseract.image_to_string(imageT,lang='kor+eng', config='-c preserve_interword_spaces=1 --psm 4')



#스폰서 필터 
def filter_sponsor(q,imageS):
    target_url = ''   
    try:            
        print(imageS['text'])
        for i in range(0,len(imageS['text'])):
            if imageS['text'][i]  == "":
                #print("통과")
                continue                        
            if "https" in imageS['text'][i]:
                #print("https가있다")
                if q in imageS['text'][i] :
                    #print("매치한다")
                    return "exist"
                else:
                    #print("매치하지않음")
                    target_url = imageS['text'][i]
                    return target_url
            else:
                pass            
    except:
        print("filter_sponsor_error")
        pass                
    return ""


def abuseClick(chinaMtype,com_name,usr):
    adclick = ''
    try:
        #print("https://www.pwa.pe.kr/adclick/?mode=getadclick&com="+com_name+"&usr="+str(usr))
        adclick = requests.get("https://www.pwa.pe.kr/adclick/?mode=getadclick&com="+com_name+"&usr="+str(usr)).json()
        #print(adclick)
        if adclick['gglkeyword']:
                        
            keyword = adclick['gglkeyword']
            mysite = adclick['mysite']
            home()
            time.sleep(0.5)
            chrome()
            time.sleep(3) 
            chrome_url_text(keyword)
            time.sleep(2)
            s_x = ''
            s_y = ''            
            s_x,s_y = find_text("스폰서","onlydata")          
            if s_x == '' and s_y == '':
                s_x,s_y = find_text("Sponsored","onlydata")
            if s_x == '' and s_y == '':
                return ""
            
            #print(str(s_x) + " " +str(s_y))
            
            #sponimage = os.getenv('USERPROFILE')+"/Desktop/chinaM/sponsor.png"
            
            #sponsers = pyautogui.locateOnScreen(sponimage,confidence=0.7)
            #print(sponsers)
              
            pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract'
     
            imageT = ImageGrab.grab().crop((s_x,s_y,s_x+400,s_y+75))#add this value 718/127
            #imageT.save('c:/Users/user/ss.png')            
            imageS = pytesseract.image_to_data(imageT, lang='kor+eng',  config='-c preserve_interword_spaces=1 --psm 4', output_type=Output.DICT)
            #print(imageS)  
            for i in range(0,len(mysite)):
                filterSponsor = filter_sponsor(mysite[i],imageS)
                if filterSponsor == "exist":
                    print("필터로 통과")
                    pass
                elif filterSponsor :
                    #print("광고클릭한다")
                    pyautogui.moveTo(s_x,s_y,1)
                    pyautogui.click(s_x,s_y)
                    time.sleep(4)
                    dna_scrolldown()
                    time.sleep(6)
                    #print("https://www.pwa.pe.kr/adclick/?mode=log&gglkeyword="+keyword+"&turl="+filterSponsor+"&com="+com_name+"&usr="+str(usr))
                    adlog = requests.get("https://www.pwa.pe.kr/adclick/?mode=log&gglkeyword="+keyword+"&turl="+filterSponsor+"&com="+com_name+"&usr="+str(usr))
                    #print(adlog)
                    return "adclick"
    except Exception as e:
        print(e)
        print("error")
        return ""
        
    
            
def main_(viewers,target,reset,program,ver):        
    while True:
        try:
            com_name , ip , usr_path , desktop_path = find_com()
            #version_update(com_name,ip,desktp_path)
            try:
                f = open(desktop_path+'/chinaM/position.txt')
                lines = f.readlines()
                con_viewers = len(lines) 
            except Exception as e:
                print(e)
                print("바탕화면의 position.txt 파일을 확인하세요 ")
                raise
            if con_viewers:
                viewers = con_viewers            
            requests.get("http://61.109.34.245/blue/?mode=set&ver="+ver+"&com="+com_name+"&ip="+ip+"&viewers="+str(con_viewers)+"&reset="+str(reset))            
            break
        except Exception as e:
            print(e)
            pass
        time.sleep(600)

    
    time.sleep(5)
    
    while True:
        msg = 'critical'
        usr = ''

        try:
            server = requests.get("http://61.109.34.245/blue/?mode=project&com="+com_name+"&ip="+ip+"&target="+str(target)).json()
            if server['team']:
                usr = server['team']
                w1,h1,chinaMtype = mobile_info(int(server['team']),str(desktop_path))
                if "@" in chinaMtype:
                    print("postion 파일에서 "+str(server['team'])+"줄 마지막에 @를 붙여 통과시키는 뷰어입니다")
                    msg = 'seterror'
                    raise
            else:
                print("할당된 뷰어가 없습니다")
                msg = 'seterror'
                raise
                        
            if server['pass'] == 'pass' and server['team'] and server['qname']:                    
                print(" ")
                print("##  최종  시청시각  "+server['viewdatetime'])                
                print("##  현재  시작시각 ",end=" ")
                print(datetime.datetime.now())
                if program != "2":
                    print("##  "+str(server['team'])+"번 "+server['qname']+" 시작 ")
                    pass
                elif program == "2":
                    print("##  "+str(server['team'])+"번 naver 시작 ")
                    pass
                pass
                
            
            chinaMtype_path = desktop_path+"/chinaM/"+chinaMtype
            print(" 모바일 모델 : "+str(chinaMtype))
            
            if blue_team(int(server['team']),str(desktop_path)) == "off":
                print("off team position")
                msg =  "seterror"
                raise
            
            #find_image("premium",2,chinaMtype_path)
            app()
            time.sleep(0.5)
            close_all()
            time.sleep(1)
            home()


            
            if server['vpncheck'] == "on":

                print("###  vpn체크를 실행합니다. ")
                for v in range(0,2):
                    vpn_recheck = ''
                    #find_image("chrome",2,chinaMtype_path)
                    home()
                    time.sleep(1)
                    chrome()
                    time.sleep(3) 
                    vpn_com = com_name.replace('k','1_')
                    vpn_com = vpn_com.replace('H','2_')
                    #ctrl_v_on('61.109.34.245/99/?_1='+vpn_com+'&_2='+usr)
                    chrome_url_text('61.109.34.245/99/?_1='+vpn_com+'&_2='+usr)
                    #pyautogui.typewrite('61.109.34.245/99/?_1='+vpn_com+'&_2='+usr,interval=0.1)
                    
                    time.sleep(2)
                    #인터넷이 안된다면 
                    if find_image("nointernet",3,chinaMtype_path) == "on":
                        vpn_recheck = 'nointernet'

                    vpn = requests.get('http://61.109.34.245/99/?json=json&_1='+vpn_com+'&_2='+usr).json()                
                    if vpn['vpn_ok'] == 'ok':                    
                        break;
                    elif vpn['vpn_ok'] == 'nointernet':
                        print("인터넷 에러입니다")                    
                        vpn_recheck = 'nointernet'
                        if v == 1:
                            raise
                    else:
                        print("VPN 에러입니다")
                        vpn_recheck = 'vpn_error'
                        if v == 1:
                            raise


                    #vpn re_check
                    if vpn_recheck != "" and v == 0:
                        print("VPN 앱실행")
                        time.sleep(0.5)
                        home()
                        time.sleep(0.5)
                        vpn_app()
                        time.sleep(1)
                        vpn_click()
                        print("VPN 클릭 10초 대기")
                        time.sleep(10)                        


            #확률로 크롭탭 닫기 실행 
            #go_tabclose = random.randint(1,18)
            #print(str(go_tabclose) + " 확률")
            #if go_tabclose <= 1 or target:
            #    print(" 크롬 탭 모두 닫기 실행")
            #    time.sleep(1)61.109.34.245/99/?_1=1_001&_2=12
            #    chrome()
            #    time.sleep(3)
            #    tab_close(chinaMtype_path)
            #else:
            #    pass


                            
            ###### 네이버 클릭 부
            #서버 프로젝트 할당이거나 시작시 네이버 설정   이거나 서버에서 dna 실행값을 받아오거나
            if server['dna_for']:
                pass
            else:
                server['dna_for'] = "1"
            
            naver_status = ''
            if ( server['qname'] == "naver" or program != "1" ) and int(server['dna_for']) >= 1 :
                
                for n in range(0,int(server['dna_for'])):
                    #네이버 클릭으로 전환
                    print(" 쇼핑 아이템 클릭 할당 " + str(n) + " 회")
                    home()            
                    try:
                         naver_status = dna(int(server['team'])) 
                    except Exception as e:
                        print(e)
                        print('error')
                if naver_status != "none":
                    msg = 'servercheck'
                    raise

                
            ####광고 클릭 부분 .
            try:
                if abuseClick(chinaMtype,com_name,usr) == "adclick":
                    print("광고 클릭성공")
                else:
                    print("광고 클릭없음")
                    pass
            except:
                print("광고 에러")
                pass
            

                
            #뷰어 프로젝트 실행                                            
            if server['pass'] == 'pass' and server['team'] and server['qname'] != 'naver' and server['qname'] and program != "2"  :
                print(server)
                home()
                time.sleep(0.5)
                youtube_open()
                time.sleep(2)
                youtube_home()
                time.sleep(0.5)
                youtube_home()
                #find_image("youtube_home",20,chinaMtype_path)
                               
                time.sleep(1)
                if find_image("search",1,chinaMtype_path) == "on":
                    time.sleep(1)
                    #korean_macro(str(server['keyword']))
                    ctrl_v_on(str(server['keyword']))
                    print(str(server['keyword']))
                    pydirectinput.press('enter')                        
                    pass
                else:
                    raise
                time.sleep(2)
            
###################채널키워드 
###################채널 키워드가 있다면 채널검색으로 들어간다. 
                if server['mchannelkeyword']:
                    #첫번째채널로들어간다. (맨처음이니까 혹시라도)
                    print("채널 :"+server['qname'] +" 검색" ,end = " ")
                    if find_text(server['qname'],"") == "findtext":
                        time.sleep(2)
                        #재생목록등 선택(회색이어서 글자추출안됨)
                        #if find_text(server['mkeyword']) == "findtext":
                        #    time.sleep(2)
                        chkey = server['mchannelkeyword'].split(",")
                        cq = random.choice(chkey)
                        print("영상 검색 : "+cq,end = " " )                        
                        for o in range(0,2):
                            dna_scrolldown()
                            if find_text(cq,"fq") == "findtext":                               
                            #msg =  screenshot(cq,usr_path,server['searchnmb'],chinaMtype_path) #check_screenshot
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
                    if filter_realtime(server['mode'],server['mode2'],chinaMtype_path) == "on":
                        #실시간필터로 들어가는 경우(실시간시청) 
                        msg  = screenshot(server['qname'],usr_path,server['searchnmb'],chinaMtype_path) #check_screenshot
                        pass
                    else:
                        msg = "failfindkeyword"
                        print("filter error")
                        raise

                time.sleep(2)                   
                
                if msg == 'viewstart' :#and check_infos('chat') == 'on'
                    if server['research'] == "o":
                        pyautogui.moveTo(954,808,1)
                        pyautogui.click(954,808)
                    elif server['research'] == "x":
                        pyautogui.moveTo(953,870,1)
                        pyautogui.click(953,870)
                        
                    time.sleep(1)

                    #chat_close()
                    find_image("chat_close",4,chinaMtype_path)
                    time.sleep(1)
                        
                    if server['like'] == 'on' or server['scrap'] == 'on' or target:

                        #like()
                        find_image("like",1,chinaMtype_path)

                        #time.sleep(0.5)
                        #scrap()
                        find_image("scrap",1,chinaMtype_path)
                        
                elif msg:
                    pass
                
        except Exception as e:
            print(e)
        if msg:
            pass

        else:
            msg = 'seterror'           
            
        
        try:
            requests.get("http://61.109.34.245/blue/?mode=log&msg="+msg+"&com="+com_name+"&ip="+ip+"&usr="+usr)
        except Exception as e:
            print(e)
            
        print(str(server['team'])+"번  " +msg)
        time.sleep(2)
        try:
            blue_ori()
        except Exception as e:
            print(e)
        print("#################")
        print (" ")
        now = time.localtime()
        print ("%04d/%02d/%02d %02d:%02d:%02d" % (now.tm_year, now.tm_mon, now.tm_mday, now.tm_hour, now.tm_min, now.tm_sec))
        print (" ")
        print("#################")
        print(" 다른 프로젝트 대기중...버전 ( "+str(ver)+" )")   
        time.sleep(5)

def tab_close(chinaMtype_path):
    pyautogui.moveTo(1128,84,1)
    pyautogui.click(1128,84)
    if find_image("3vdot",3,chinaMtype_path):
        pass
    else:
        return "off"
    if find_text("닫기","") == "findtext":
        pass
    else:
        return "off"
    if find_image("tabclose",2,chinaMtype_path):
        pass
    else:
        return "off"


    
    
def version_update(com_name,ip,desktp_path):
    APP_NAME = 'blue'
    
    APP_VERSION = '1.0.0.0'
    try:
        print(APP_NAME + " " + APP_VERSION )
        
        #find current user desktop path
        desktop_path = os.getenv('USERPROFILE')+'/Desktop'
        if os.path.isfile(desktop_path+"/blue_.exe"):
            os.remove(desktop_path+"/blue_.exe")
            pass
         
        app_version =  requests.get("http:///61.109.34.245/blue/?version="+APP_VERSION+"&com="+com_name).json()
        print("http:///61.109.34.245/blue/?version="+APP_VERSION+"&com="+com_name)
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
        f = open(desktop_path+'/chinaM/position.txt')
        lines = f.readlines()
    except Exception as e:
        print(e)
        print("바탕화면의 position.txt 파일을 확인하세요 ")
        pass


def ftp_folder_upload(mtype):    
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(mtype + " 타입 업데이트 start ")
    mtypepath = os.getenv('USERPROFILE')+"/Desktop/chinaM/"+mtype
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
    
def ftp_folder_download(mtype):    
    host = '61.109.34.245'
    user = 'rank_zenpc'
    passwd = 'qetuo00!!@@'
    print(mtype + " 타입 다운로드 start ")
    mtypepath = os.getenv('USERPROFILE')+"/Desktop/chinaM/"+mtype
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
    mtypepath = os.getenv('USERPROFILE')+"/Desktop/chinaM/koreaM.py"
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
                ftp.storbinary('STOR koreaM.py' , wf)
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
    mtypepath = os.getenv('USERPROFILE')+"/Desktop/chinaM/koreaM.py"
     
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
                ftp.retrbinary('RETR koreaM.py' , wf.write)
            print(ftp.dir())
     
    except Exception as e:
        print(e)
        print("FTP 파이썬 파일 다운로드 에러")
        
    print( " 파이썬파일  다운로드  완 " ,end = " ")
    print(datetime.datetime.now())
    print("-----------------------------------------------------")




#test_screenshot()
#time.sleep(10000)



    
viewers = 40  
ver = '1.0.3.1.4'
com_check = socket.gethostname()

print("")
print("################################")
print("blue - "+str(ver) + " 버전 시작 ")
print("")





while True:
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
    reset = input("초기화를 원하지 않으면 1 초기화를 원하면 엔터  : ")    
    if reset == "":
        reset = "1"
        break
    elif reset == "1":
        reset = ""
        break

print("")

while True:
    spcviewer = input("특정 뷰어로 실행하시려면 번호를 입력하세요 아니면 엔터 : ")
    if spcviewer == "":
        break
    elif int(spcviewer) >=1:
        break

print("")

while True:
    if com_check == "k001":
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

    


if spcviewer.isdigit():
    if int(spcviewer) >= 1:
        target = spcviewer
        print(str(target) + "번 뷰어로 직접 "+program_msg +"실행하기")
        main_(viewers,target,reset,program,ver)
    else:
        
        
        if com_check == "k001" and sys.argv[1] == com_check:
            ftp_update()
        elif sys.argv[1] == com_check:
            ftp_download()
else:
    main_(viewers,0,reset,program,ver)


