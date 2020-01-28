<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE cornumpdesconto
class cl_cornumpdesconto { 
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
   var $k12_id = 0; 
   var $k12_data_dia = null; 
   var $k12_data_mes = null; 
   var $k12_data_ano = null; 
   var $k12_data = null; 
   var $k12_autent = 0; 
   var $k12_numpre = 0; 
   var $k12_numpar = 0; 
   var $k12_numtot = 0; 
   var $k12_numdig = 0; 
   var $k12_receit = 0; 
   var $k12_valor = 0; 
   var $k12_numnov = 0; 
   var $k12_receitaprincipal = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k12_id = int4 = Autenticação 
                 k12_data = date = Data Autenticação 
                 k12_autent = int4 = Código Autenticação 
                 k12_numpre = int4 = Código de Arrecadação 
                 k12_numpar = int4 = Número Parcela 
                 k12_numtot = int4 = Numero Total 
                 k12_numdig = int4 = Digito 
                 k12_receit = int4 = Código da receita 
                 k12_valor = float8 = Valor Autenticação 
                 k12_numnov = int4 = Numpre Novo 
                 k12_receitaprincipal = int4 = Receita principal do desconto 
                 ";
   //funcao construtor da classe 
   function cl_cornumpdesconto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cornumpdesconto"); 
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
       $this->k12_id = ($this->k12_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_id"]:$this->k12_id);
       if($this->k12_data == ""){
         $this->k12_data_dia = ($this->k12_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]:$this->k12_data_dia);
         $this->k12_data_mes = ($this->k12_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_mes"]:$this->k12_data_mes);
         $this->k12_data_ano = ($this->k12_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_ano"]:$this->k12_data_ano);
         if($this->k12_data_dia != ""){
            $this->k12_data = $this->k12_data_ano."-".$this->k12_data_mes."-".$this->k12_data_dia;
         }
       }
       $this->k12_autent = ($this->k12_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_autent"]:$this->k12_autent);
       $this->k12_numpre = ($this->k12_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_numpre"]:$this->k12_numpre);
       $this->k12_numpar = ($this->k12_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_numpar"]:$this->k12_numpar);
       $this->k12_numtot = ($this->k12_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_numtot"]:$this->k12_numtot);
       $this->k12_numdig = ($this->k12_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_numdig"]:$this->k12_numdig);
       $this->k12_receit = ($this->k12_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_receit"]:$this->k12_receit);
       $this->k12_valor = ($this->k12_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_valor"]:$this->k12_valor);
       $this->k12_numnov = ($this->k12_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_numnov"]:$this->k12_numnov);
       $this->k12_receitaprincipal = ($this->k12_receitaprincipal == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_receitaprincipal"]:$this->k12_receitaprincipal);
     }else{
       $this->k12_id = ($this->k12_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_id"]:$this->k12_id);
       $this->k12_data = ($this->k12_data == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_data_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["k12_data_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]:$this->k12_data);
       $this->k12_autent = ($this->k12_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_autent"]:$this->k12_autent);
       $this->k12_numpre = ($this->k12_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_numpre"]:$this->k12_numpre);
       $this->k12_numpar = ($this->k12_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_numpar"]:$this->k12_numpar);
       $this->k12_receit = ($this->k12_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k12_receit"]:$this->k12_receit);
     }
   }
   // funcao para inclusao
   function incluir ($k12_id,$k12_data,$k12_autent,$k12_numpre,$k12_numpar,$k12_receit){ 
      $this->atualizacampos();
     if($this->k12_numtot == null ){ 
       $this->erro_sql = " Campo Numero Total nao Informado.";
       $this->erro_campo = "k12_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_numdig == null ){ 
       $this->erro_sql = " Campo Digito nao Informado.";
       $this->erro_campo = "k12_numdig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_valor == null ){ 
       $this->erro_sql = " Campo Valor Autenticação nao Informado.";
       $this->erro_campo = "k12_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k12_numnov == null ){ 
       $this->k12_numnov = "0";
     }
     if($this->k12_receitaprincipal == null ){ 
       $this->erro_sql = " Campo Receita principal do desconto nao Informado.";
       $this->erro_campo = "k12_receitaprincipal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->k12_id = $k12_id; 
       $this->k12_data = $k12_data; 
       $this->k12_autent = $k12_autent; 
       $this->k12_numpre = $k12_numpre; 
       $this->k12_numpar = $k12_numpar; 
       $this->k12_receit = $k12_receit; 
     if(($this->k12_id == null) || ($this->k12_id == "") ){ 
       $this->erro_sql = " Campo k12_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_data == null) || ($this->k12_data == "") ){ 
       $this->erro_sql = " Campo k12_data nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_autent == null) || ($this->k12_autent == "") ){ 
       $this->erro_sql = " Campo k12_autent nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_numpre == null) || ($this->k12_numpre == "") ){ 
       $this->erro_sql = " Campo k12_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_numpar == null) || ($this->k12_numpar == "") ){ 
       $this->erro_sql = " Campo k12_numpar nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k12_receit == null) || ($this->k12_receit == "") ){ 
       $this->erro_sql = " Campo k12_receit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cornumpdesconto(
                                       k12_id 
                                      ,k12_data 
                                      ,k12_autent 
                                      ,k12_numpre 
                                      ,k12_numpar 
                                      ,k12_numtot 
                                      ,k12_numdig 
                                      ,k12_receit 
                                      ,k12_valor 
                                      ,k12_numnov 
                                      ,k12_receitaprincipal 
                       )
                values (
                                $this->k12_id 
                               ,".($this->k12_data == "null" || $this->k12_data == ""?"null":"'".$this->k12_data."'")." 
                               ,$this->k12_autent 
                               ,$this->k12_numpre 
                               ,$this->k12_numpar 
                               ,$this->k12_numtot 
                               ,$this->k12_numdig 
                               ,$this->k12_receit 
                               ,$this->k12_valor 
                               ,$this->k12_numnov 
                               ,$this->k12_receitaprincipal 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k12_id."-".$this->k12_data."-".$this->k12_autent."-".$this->k12_numpre."-".$this->k12_numpar."-".$this->k12_receit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k12_id."-".$this->k12_data."-".$this->k12_autent."-".$this->k12_numpre."-".$this->k12_numpar."-".$this->k12_receit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent."-".$this->k12_numpre."-".$this->k12_numpar."-".$this->k12_receit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k12_id,$this->k12_data,$this->k12_autent,$this->k12_numpre,$this->k12_numpar,$this->k12_receit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1139,'$this->k12_id','I')");
       $resac = db_query("insert into db_acountkey values($acount,1140,'$this->k12_data','I')");
       $resac = db_query("insert into db_acountkey values($acount,1141,'$this->k12_autent','I')");
       $resac = db_query("insert into db_acountkey values($acount,1146,'$this->k12_numpre','I')");
       $resac = db_query("insert into db_acountkey values($acount,1147,'$this->k12_numpar','I')");
       $resac = db_query("insert into db_acountkey values($acount,1150,'$this->k12_receit','I')");
       $resac = db_query("insert into db_acount values($acount,3499,1139,'','".AddSlashes(pg_result($resaco,0,'k12_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,1140,'','".AddSlashes(pg_result($resaco,0,'k12_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,1141,'','".AddSlashes(pg_result($resaco,0,'k12_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,1146,'','".AddSlashes(pg_result($resaco,0,'k12_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,1147,'','".AddSlashes(pg_result($resaco,0,'k12_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,1148,'','".AddSlashes(pg_result($resaco,0,'k12_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,1149,'','".AddSlashes(pg_result($resaco,0,'k12_numdig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,1150,'','".AddSlashes(pg_result($resaco,0,'k12_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,1144,'','".AddSlashes(pg_result($resaco,0,'k12_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,2022,'','".AddSlashes(pg_result($resaco,0,'k12_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3499,19817,'','".AddSlashes(pg_result($resaco,0,'k12_receitaprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k12_id=null,$k12_data=null,$k12_autent=null,$k12_numpre=null,$k12_numpar=null,$k12_receit=null) { 
      $this->atualizacampos();
     $sql = " update cornumpdesconto set ";
     $virgula = "";
     if(trim($this->k12_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_id"])){ 
       $sql  .= $virgula." k12_id = $this->k12_id ";
       $virgula = ",";
       if(trim($this->k12_id) == null ){ 
         $this->erro_sql = " Campo Autenticação nao Informado.";
         $this->erro_campo = "k12_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"] !="") ){ 
       $sql  .= $virgula." k12_data = '$this->k12_data' ";
       $virgula = ",";
       if(trim($this->k12_data) == null ){ 
         $this->erro_sql = " Campo Data Autenticação nao Informado.";
         $this->erro_campo = "k12_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k12_data_dia"])){ 
         $sql  .= $virgula." k12_data = null ";
         $virgula = ",";
         if(trim($this->k12_data) == null ){ 
           $this->erro_sql = " Campo Data Autenticação nao Informado.";
           $this->erro_campo = "k12_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k12_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_autent"])){ 
       $sql  .= $virgula." k12_autent = $this->k12_autent ";
       $virgula = ",";
       if(trim($this->k12_autent) == null ){ 
         $this->erro_sql = " Campo Código Autenticação nao Informado.";
         $this->erro_campo = "k12_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_numpre"])){ 
       $sql  .= $virgula." k12_numpre = $this->k12_numpre ";
       $virgula = ",";
       if(trim($this->k12_numpre) == null ){ 
         $this->erro_sql = " Campo Código de Arrecadação nao Informado.";
         $this->erro_campo = "k12_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_numpar"])){ 
       $sql  .= $virgula." k12_numpar = $this->k12_numpar ";
       $virgula = ",";
       if(trim($this->k12_numpar) == null ){ 
         $this->erro_sql = " Campo Número Parcela nao Informado.";
         $this->erro_campo = "k12_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_numtot"])){ 
       $sql  .= $virgula." k12_numtot = $this->k12_numtot ";
       $virgula = ",";
       if(trim($this->k12_numtot) == null ){ 
         $this->erro_sql = " Campo Numero Total nao Informado.";
         $this->erro_campo = "k12_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_numdig"])){ 
       $sql  .= $virgula." k12_numdig = $this->k12_numdig ";
       $virgula = ",";
       if(trim($this->k12_numdig) == null ){ 
         $this->erro_sql = " Campo Digito nao Informado.";
         $this->erro_campo = "k12_numdig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_receit"])){ 
       $sql  .= $virgula." k12_receit = $this->k12_receit ";
       $virgula = ",";
       if(trim($this->k12_receit) == null ){ 
         $this->erro_sql = " Campo Código da receita nao Informado.";
         $this->erro_campo = "k12_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_valor"])){ 
       $sql  .= $virgula." k12_valor = $this->k12_valor ";
       $virgula = ",";
       if(trim($this->k12_valor) == null ){ 
         $this->erro_sql = " Campo Valor Autenticação nao Informado.";
         $this->erro_campo = "k12_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k12_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_numnov"])){ 
        if(trim($this->k12_numnov)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k12_numnov"])){ 
           $this->k12_numnov = "0" ; 
        } 
       $sql  .= $virgula." k12_numnov = $this->k12_numnov ";
       $virgula = ",";
     }
     if(trim($this->k12_receitaprincipal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k12_receitaprincipal"])){ 
       $sql  .= $virgula." k12_receitaprincipal = $this->k12_receitaprincipal ";
       $virgula = ",";
       if(trim($this->k12_receitaprincipal) == null ){ 
         $this->erro_sql = " Campo Receita principal do desconto nao Informado.";
         $this->erro_campo = "k12_receitaprincipal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k12_id!=null){
       $sql .= " k12_id = $this->k12_id";
     }
     if($k12_data!=null){
       $sql .= " and  k12_data = '$this->k12_data'";
     }
     if($k12_autent!=null){
       $sql .= " and  k12_autent = $this->k12_autent";
     }
     if($k12_numpre!=null){
       $sql .= " and  k12_numpre = $this->k12_numpre";
     }
     if($k12_numpar!=null){
       $sql .= " and  k12_numpar = $this->k12_numpar";
     }
     if($k12_receit!=null){
       $sql .= " and  k12_receit = $this->k12_receit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k12_id,$this->k12_data,$this->k12_autent,$this->k12_numpre,$this->k12_numpar,$this->k12_receit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1139,'$this->k12_id','A')");
         $resac = db_query("insert into db_acountkey values($acount,1140,'$this->k12_data','A')");
         $resac = db_query("insert into db_acountkey values($acount,1141,'$this->k12_autent','A')");
         $resac = db_query("insert into db_acountkey values($acount,1146,'$this->k12_numpre','A')");
         $resac = db_query("insert into db_acountkey values($acount,1147,'$this->k12_numpar','A')");
         $resac = db_query("insert into db_acountkey values($acount,1150,'$this->k12_receit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_id"]) || $this->k12_id != "")
           $resac = db_query("insert into db_acount values($acount,3499,1139,'".AddSlashes(pg_result($resaco,$conresaco,'k12_id'))."','$this->k12_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_data"]) || $this->k12_data != "")
           $resac = db_query("insert into db_acount values($acount,3499,1140,'".AddSlashes(pg_result($resaco,$conresaco,'k12_data'))."','$this->k12_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_autent"]) || $this->k12_autent != "")
           $resac = db_query("insert into db_acount values($acount,3499,1141,'".AddSlashes(pg_result($resaco,$conresaco,'k12_autent'))."','$this->k12_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_numpre"]) || $this->k12_numpre != "")
           $resac = db_query("insert into db_acount values($acount,3499,1146,'".AddSlashes(pg_result($resaco,$conresaco,'k12_numpre'))."','$this->k12_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_numpar"]) || $this->k12_numpar != "")
           $resac = db_query("insert into db_acount values($acount,3499,1147,'".AddSlashes(pg_result($resaco,$conresaco,'k12_numpar'))."','$this->k12_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_numtot"]) || $this->k12_numtot != "")
           $resac = db_query("insert into db_acount values($acount,3499,1148,'".AddSlashes(pg_result($resaco,$conresaco,'k12_numtot'))."','$this->k12_numtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_numdig"]) || $this->k12_numdig != "")
           $resac = db_query("insert into db_acount values($acount,3499,1149,'".AddSlashes(pg_result($resaco,$conresaco,'k12_numdig'))."','$this->k12_numdig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_receit"]) || $this->k12_receit != "")
           $resac = db_query("insert into db_acount values($acount,3499,1150,'".AddSlashes(pg_result($resaco,$conresaco,'k12_receit'))."','$this->k12_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_valor"]) || $this->k12_valor != "")
           $resac = db_query("insert into db_acount values($acount,3499,1144,'".AddSlashes(pg_result($resaco,$conresaco,'k12_valor'))."','$this->k12_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_numnov"]) || $this->k12_numnov != "")
           $resac = db_query("insert into db_acount values($acount,3499,2022,'".AddSlashes(pg_result($resaco,$conresaco,'k12_numnov'))."','$this->k12_numnov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k12_receitaprincipal"]) || $this->k12_receitaprincipal != "")
           $resac = db_query("insert into db_acount values($acount,3499,19817,'".AddSlashes(pg_result($resaco,$conresaco,'k12_receitaprincipal'))."','$this->k12_receitaprincipal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent."-".$this->k12_numpre."-".$this->k12_numpar."-".$this->k12_receit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent."-".$this->k12_numpre."-".$this->k12_numpar."-".$this->k12_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k12_id."-".$this->k12_data."-".$this->k12_autent."-".$this->k12_numpre."-".$this->k12_numpar."-".$this->k12_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k12_id=null,$k12_data=null,$k12_autent=null,$k12_numpre=null,$k12_numpar=null,$k12_receit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k12_id,$k12_data,$k12_autent,$k12_numpre,$k12_numpar,$k12_receit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1139,'$k12_id','E')");
         $resac = db_query("insert into db_acountkey values($acount,1140,'$k12_data','E')");
         $resac = db_query("insert into db_acountkey values($acount,1141,'$k12_autent','E')");
         $resac = db_query("insert into db_acountkey values($acount,1146,'$k12_numpre','E')");
         $resac = db_query("insert into db_acountkey values($acount,1147,'$k12_numpar','E')");
         $resac = db_query("insert into db_acountkey values($acount,1150,'$k12_receit','E')");
         $resac = db_query("insert into db_acount values($acount,3499,1139,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,1140,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,1141,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,1146,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,1147,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,1148,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,1149,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_numdig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,1150,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,1144,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,2022,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3499,19817,'','".AddSlashes(pg_result($resaco,$iresaco,'k12_receitaprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cornumpdesconto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k12_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_id = $k12_id ";
        }
        if($k12_data != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_data = '$k12_data' ";
        }
        if($k12_autent != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_autent = $k12_autent ";
        }
        if($k12_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_numpre = $k12_numpre ";
        }
        if($k12_numpar != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_numpar = $k12_numpar ";
        }
        if($k12_receit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k12_receit = $k12_receit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent."-".$k12_numpre."-".$k12_numpar."-".$k12_receit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent."-".$k12_numpre."-".$k12_numpar."-".$k12_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k12_id."-".$k12_data."-".$k12_autent."-".$k12_numpre."-".$k12_numpar."-".$k12_receit;
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
        $this->erro_sql   = "Record Vazio na Tabela:cornumpdesconto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k12_id=null,$k12_data=null,$k12_autent=null,$k12_numpre=null,$k12_numpar=null,$k12_receit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cornumpdesconto ";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_id!=null ){
         $sql2 .= " where cornumpdesconto.k12_id = $k12_id "; 
       } 
       if($k12_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_data = '$k12_data' "; 
       } 
       if($k12_autent!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_autent = $k12_autent "; 
       } 
       if($k12_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_numpre = $k12_numpre "; 
       } 
       if($k12_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_numpar = $k12_numpar "; 
       } 
       if($k12_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_receit = $k12_receit "; 
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
   // funcao do sql 
   function sql_query_file ( $k12_id=null,$k12_data=null,$k12_autent=null,$k12_numpre=null,$k12_numpar=null,$k12_receit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cornumpdesconto ";
     $sql2 = "";
     if($dbwhere==""){
       if($k12_id!=null ){
         $sql2 .= " where cornumpdesconto.k12_id = $k12_id "; 
       } 
       if($k12_data!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_data = '$k12_data' "; 
       } 
       if($k12_autent!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_autent = $k12_autent "; 
       } 
       if($k12_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_numpre = $k12_numpre "; 
       } 
       if($k12_numpar!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_numpar = $k12_numpar "; 
       } 
       if($k12_receit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cornumpdesconto.k12_receit = $k12_receit "; 
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