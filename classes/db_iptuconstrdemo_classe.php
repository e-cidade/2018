<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: cadastro
//CLASSE DA ENTIDADE iptuconstrdemo
class cl_iptuconstrdemo { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $j60_matric = 0; 
   var $j60_idcons = 0; 
   var $j60_seq = 0; 
   var $j60_codproc = null; 
   var $j60_area = 0; 
   var $j60_datademo_dia = null; 
   var $j60_datademo_mes = null; 
   var $j60_datademo_ano = null; 
   var $j60_datademo = null; 
   var $j60_hora = null; 
   var $j60_usuario = 0; 
   var $j60_data_dia = null; 
   var $j60_data_mes = null; 
   var $j60_data_ano = null; 
   var $j60_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j60_matric = int4 = Inscrição Imóvel 
                 j60_idcons = int4 = Codigo Construcao 
                 j60_seq = int4 = Sequencia 
                 j60_codproc = varchar(20) = Processo 
                 j60_area = float8 = Area 
                 j60_datademo = date = Demolição 
                 j60_hora = varchar(5) = Hora 
                 j60_usuario = int4 = Cod. Usuário 
                 j60_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_iptuconstrdemo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptuconstrdemo"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->j60_matric = ($this->j60_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_matric"]:$this->j60_matric);
       $this->j60_idcons = ($this->j60_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_idcons"]:$this->j60_idcons);
       $this->j60_seq = ($this->j60_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_seq"]:$this->j60_seq);
       $this->j60_codproc = ($this->j60_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_codproc"]:$this->j60_codproc);
       $this->j60_area = ($this->j60_area == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_area"]:$this->j60_area);
       if($this->j60_datademo == ""){
         $this->j60_datademo_dia = ($this->j60_datademo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_datademo_dia"]:$this->j60_datademo_dia);
         $this->j60_datademo_mes = ($this->j60_datademo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_datademo_mes"]:$this->j60_datademo_mes);
         $this->j60_datademo_ano = ($this->j60_datademo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_datademo_ano"]:$this->j60_datademo_ano);
         if($this->j60_datademo_dia != ""){
            $this->j60_datademo = $this->j60_datademo_ano."-".$this->j60_datademo_mes."-".$this->j60_datademo_dia;
         }
       }
       $this->j60_hora = ($this->j60_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_hora"]:$this->j60_hora);
       $this->j60_usuario = ($this->j60_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_usuario"]:$this->j60_usuario);
       if($this->j60_data == ""){
         $this->j60_data_dia = ($this->j60_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_data_dia"]:$this->j60_data_dia);
         $this->j60_data_mes = ($this->j60_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_data_mes"]:$this->j60_data_mes);
         $this->j60_data_ano = ($this->j60_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_data_ano"]:$this->j60_data_ano);
         if($this->j60_data_dia != ""){
            $this->j60_data = $this->j60_data_ano."-".$this->j60_data_mes."-".$this->j60_data_dia;
         }
       }
     }else{
       $this->j60_matric = ($this->j60_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_matric"]:$this->j60_matric);
       $this->j60_idcons = ($this->j60_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_idcons"]:$this->j60_idcons);
       $this->j60_seq = ($this->j60_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["j60_seq"]:$this->j60_seq);
     }
   }
   // funcao para inclusao
   function incluir ($j60_matric,$j60_idcons,$j60_seq){ 
      $this->atualizacampos();
     if($this->j60_codproc == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "j60_codproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j60_area == null ){ 
       $this->erro_sql = " Campo Area nao Informado.";
       $this->erro_campo = "j60_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j60_datademo == null ){ 
       $this->erro_sql = " Campo Demolição nao Informado.";
       $this->erro_campo = "j60_datademo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j60_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "j60_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j60_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "j60_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j60_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "j60_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j60_matric = $j60_matric; 
       $this->j60_idcons = $j60_idcons; 
       $this->j60_seq = $j60_seq; 
     if(($this->j60_matric == null) || ($this->j60_matric == "") ){ 
       $this->erro_sql = " Campo j60_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j60_idcons == null) || ($this->j60_idcons == "") ){ 
       $this->erro_sql = " Campo j60_idcons nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j60_seq == null) || ($this->j60_seq == "") ){ 
       $this->erro_sql = " Campo j60_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptuconstrdemo(
                                       j60_matric 
                                      ,j60_idcons 
                                      ,j60_seq 
                                      ,j60_codproc 
                                      ,j60_area 
                                      ,j60_datademo 
                                      ,j60_hora 
                                      ,j60_usuario 
                                      ,j60_data 
                       )
                values (
                                $this->j60_matric 
                               ,$this->j60_idcons 
                               ,$this->j60_seq 
                               ,'$this->j60_codproc' 
                               ,$this->j60_area 
                               ,".($this->j60_datademo == "null" || $this->j60_datademo == ""?"null":"'".$this->j60_datademo."'")." 
                               ,'$this->j60_hora' 
                               ,$this->j60_usuario 
                               ,".($this->j60_data == "null" || $this->j60_data == ""?"null":"'".$this->j60_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Demolição de construção ($this->j60_matric."-".$this->j60_idcons."-".$this->j60_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Demolição de construção já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Demolição de construção ($this->j60_matric."-".$this->j60_idcons."-".$this->j60_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j60_matric."-".$this->j60_idcons."-".$this->j60_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j60_matric,$this->j60_idcons,$this->j60_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5076,'$this->j60_matric','I')");
       $resac = db_query("insert into db_acountkey values($acount,5077,'$this->j60_idcons','I')");
       $resac = db_query("insert into db_acountkey values($acount,5080,'$this->j60_seq','I')");
       $resac = db_query("insert into db_acount values($acount,723,5076,'','".AddSlashes(pg_result($resaco,0,'j60_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,723,5077,'','".AddSlashes(pg_result($resaco,0,'j60_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,723,5080,'','".AddSlashes(pg_result($resaco,0,'j60_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,723,5078,'','".AddSlashes(pg_result($resaco,0,'j60_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,723,5083,'','".AddSlashes(pg_result($resaco,0,'j60_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,723,5084,'','".AddSlashes(pg_result($resaco,0,'j60_datademo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,723,5081,'','".AddSlashes(pg_result($resaco,0,'j60_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,723,5079,'','".AddSlashes(pg_result($resaco,0,'j60_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,723,5082,'','".AddSlashes(pg_result($resaco,0,'j60_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j60_matric=null,$j60_idcons=null,$j60_seq=null) { 
      $this->atualizacampos();
     $sql = " update iptuconstrdemo set ";
     $virgula = "";
     if(trim($this->j60_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_matric"])){ 
       $sql  .= $virgula." j60_matric = $this->j60_matric ";
       $virgula = ",";
       if(trim($this->j60_matric) == null ){ 
         $this->erro_sql = " Campo Inscrição Imóvel nao Informado.";
         $this->erro_campo = "j60_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j60_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_idcons"])){ 
       $sql  .= $virgula." j60_idcons = $this->j60_idcons ";
       $virgula = ",";
       if(trim($this->j60_idcons) == null ){ 
         $this->erro_sql = " Campo Codigo Construcao nao Informado.";
         $this->erro_campo = "j60_idcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j60_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_seq"])){ 
       $sql  .= $virgula." j60_seq = $this->j60_seq ";
       $virgula = ",";
       if(trim($this->j60_seq) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "j60_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j60_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_codproc"])){ 
       $sql  .= $virgula." j60_codproc = '$this->j60_codproc' ";
       $virgula = ",";
       if(trim($this->j60_codproc) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "j60_codproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j60_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_area"])){ 
       $sql  .= $virgula." j60_area = $this->j60_area ";
       $virgula = ",";
       if(trim($this->j60_area) == null ){ 
         $this->erro_sql = " Campo Area nao Informado.";
         $this->erro_campo = "j60_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j60_datademo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_datademo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j60_datademo_dia"] !="") ){ 
       $sql  .= $virgula." j60_datademo = '$this->j60_datademo' ";
       $virgula = ",";
       if(trim($this->j60_datademo) == null ){ 
         $this->erro_sql = " Campo Demolição nao Informado.";
         $this->erro_campo = "j60_datademo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j60_datademo_dia"])){ 
         $sql  .= $virgula." j60_datademo = null ";
         $virgula = ",";
         if(trim($this->j60_datademo) == null ){ 
           $this->erro_sql = " Campo Demolição nao Informado.";
           $this->erro_campo = "j60_datademo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j60_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_hora"])){ 
       $sql  .= $virgula." j60_hora = '$this->j60_hora' ";
       $virgula = ",";
       if(trim($this->j60_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "j60_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j60_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_usuario"])){ 
       $sql  .= $virgula." j60_usuario = $this->j60_usuario ";
       $virgula = ",";
       if(trim($this->j60_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "j60_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j60_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j60_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j60_data_dia"] !="") ){ 
       $sql  .= $virgula." j60_data = '$this->j60_data' ";
       $virgula = ",";
       if(trim($this->j60_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "j60_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j60_data_dia"])){ 
         $sql  .= $virgula." j60_data = null ";
         $virgula = ",";
         if(trim($this->j60_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "j60_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($j60_matric!=null){
       $sql .= " j60_matric = $this->j60_matric";
     }
     if($j60_idcons!=null){
       $sql .= " and  j60_idcons = $this->j60_idcons";
     }
     if($j60_seq!=null){
       $sql .= " and  j60_seq = $this->j60_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j60_matric,$this->j60_idcons,$this->j60_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5076,'$this->j60_matric','A')");
         $resac = db_query("insert into db_acountkey values($acount,5077,'$this->j60_idcons','A')");
         $resac = db_query("insert into db_acountkey values($acount,5080,'$this->j60_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_matric"]))
           $resac = db_query("insert into db_acount values($acount,723,5076,'".AddSlashes(pg_result($resaco,$conresaco,'j60_matric'))."','$this->j60_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_idcons"]))
           $resac = db_query("insert into db_acount values($acount,723,5077,'".AddSlashes(pg_result($resaco,$conresaco,'j60_idcons'))."','$this->j60_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_seq"]))
           $resac = db_query("insert into db_acount values($acount,723,5080,'".AddSlashes(pg_result($resaco,$conresaco,'j60_seq'))."','$this->j60_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_codproc"]))
           $resac = db_query("insert into db_acount values($acount,723,5078,'".AddSlashes(pg_result($resaco,$conresaco,'j60_codproc'))."','$this->j60_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_area"]))
           $resac = db_query("insert into db_acount values($acount,723,5083,'".AddSlashes(pg_result($resaco,$conresaco,'j60_area'))."','$this->j60_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_datademo"]))
           $resac = db_query("insert into db_acount values($acount,723,5084,'".AddSlashes(pg_result($resaco,$conresaco,'j60_datademo'))."','$this->j60_datademo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_hora"]))
           $resac = db_query("insert into db_acount values($acount,723,5081,'".AddSlashes(pg_result($resaco,$conresaco,'j60_hora'))."','$this->j60_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_usuario"]))
           $resac = db_query("insert into db_acount values($acount,723,5079,'".AddSlashes(pg_result($resaco,$conresaco,'j60_usuario'))."','$this->j60_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j60_data"]))
           $resac = db_query("insert into db_acount values($acount,723,5082,'".AddSlashes(pg_result($resaco,$conresaco,'j60_data'))."','$this->j60_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Demolição de construção nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j60_matric."-".$this->j60_idcons."-".$this->j60_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Demolição de construção nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j60_matric."-".$this->j60_idcons."-".$this->j60_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j60_matric."-".$this->j60_idcons."-".$this->j60_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j60_matric=null,$j60_idcons=null,$j60_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j60_matric,$j60_idcons,$j60_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5076,'$j60_matric','E')");
         $resac = db_query("insert into db_acountkey values($acount,5077,'$j60_idcons','E')");
         $resac = db_query("insert into db_acountkey values($acount,5080,'$j60_seq','E')");
         $resac = db_query("insert into db_acount values($acount,723,5076,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,723,5077,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,723,5080,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,723,5078,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,723,5083,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,723,5084,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_datademo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,723,5081,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,723,5079,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,723,5082,'','".AddSlashes(pg_result($resaco,$iresaco,'j60_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptuconstrdemo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j60_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j60_matric = $j60_matric ";
        }
        if($j60_idcons != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j60_idcons = $j60_idcons ";
        }
        if($j60_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j60_seq = $j60_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Demolição de construção nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j60_matric."-".$j60_idcons."-".$j60_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Demolição de construção nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j60_matric."-".$j60_idcons."-".$j60_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j60_matric."-".$j60_idcons."-".$j60_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:iptuconstrdemo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j60_matric=null,$j60_idcons=null,$j60_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from iptuconstrdemo ";
     $sql .= "      inner join iptuconstr  on  iptuconstr.j39_matric = iptuconstrdemo.j60_matric and  iptuconstr.j39_idcons = iptuconstrdemo.j60_idcons";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuconstr.j39_matric";
     $sql .= "      inner join ruas  as a on   a.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  as b on   b.j01_matric = iptuconstr.j39_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j60_matric!=null ){
         $sql2 .= " where iptuconstrdemo.j60_matric = $j60_matric "; 
       } 
       if($j60_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptuconstrdemo.j60_idcons = $j60_idcons "; 
       } 
       if($j60_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptuconstrdemo.j60_seq = $j60_seq "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $j60_matric=null,$j60_idcons=null,$j60_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from iptuconstrdemo ";
     $sql2 = "";
     if($dbwhere==""){
       if($j60_matric!=null ){
         $sql2 .= " where iptuconstrdemo.j60_matric = $j60_matric "; 
       } 
       if($j60_idcons!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptuconstrdemo.j60_idcons = $j60_idcons "; 
       } 
       if($j60_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " iptuconstrdemo.j60_seq = $j60_seq "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>