<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE iptuconstrhabite
class cl_iptuconstrhabite { 
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
   var $j131_sequencial = 0; 
   var $j131_idcons = 0; 
   var $j131_matric = 0; 
   var $j131_usuario = 0; 
   var $j131_codprot = null; 
   var $j131_dtprot_dia = null; 
   var $j131_dtprot_mes = null; 
   var $j131_dtprot_ano = null; 
   var $j131_dtprot = null; 
   var $j131_cadhab = null; 
   var $j131_dthabite_dia = null; 
   var $j131_dthabite_mes = null; 
   var $j131_dthabite_ano = null; 
   var $j131_dthabite = null; 
   var $j131_data_dia = null; 
   var $j131_data_mes = null; 
   var $j131_data_ano = null; 
   var $j131_data = null; 
   var $j131_hora = null; 
   var $j131_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j131_sequencial = int4 = Sequencial 
                 j131_idcons = int4 = Constru��o 
                 j131_matric = int4 = Matricula 
                 j131_usuario = int4 = Usu�rio 
                 j131_codprot = varchar(20) = Processo de Protocolo 
                 j131_dtprot = date = Data do Processo de Protocolo 
                 j131_cadhab = varchar(20) = Habite-se 
                 j131_dthabite = date = Data do Habite-se 
                 j131_data = date = Data 
                 j131_hora = varchar(5) = Hora 
                 j131_obs = text = Observa��es 
                 ";
   //funcao construtor da classe 
   function cl_iptuconstrhabite() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptuconstrhabite"); 
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
       $this->j131_sequencial = ($this->j131_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_sequencial"]:$this->j131_sequencial);
       $this->j131_idcons = ($this->j131_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_idcons"]:$this->j131_idcons);
       $this->j131_matric = ($this->j131_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_matric"]:$this->j131_matric);
       $this->j131_usuario = ($this->j131_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_usuario"]:$this->j131_usuario);
       $this->j131_codprot = ($this->j131_codprot == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_codprot"]:$this->j131_codprot);
       if($this->j131_dtprot == ""){
         $this->j131_dtprot_dia = ($this->j131_dtprot_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_dtprot_dia"]:$this->j131_dtprot_dia);
         $this->j131_dtprot_mes = ($this->j131_dtprot_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_dtprot_mes"]:$this->j131_dtprot_mes);
         $this->j131_dtprot_ano = ($this->j131_dtprot_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_dtprot_ano"]:$this->j131_dtprot_ano);
         if($this->j131_dtprot_dia != ""){
            $this->j131_dtprot = $this->j131_dtprot_ano."-".$this->j131_dtprot_mes."-".$this->j131_dtprot_dia;
         }
       }
       $this->j131_cadhab = ($this->j131_cadhab == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_cadhab"]:$this->j131_cadhab);
       if($this->j131_dthabite == ""){
         $this->j131_dthabite_dia = ($this->j131_dthabite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_dthabite_dia"]:$this->j131_dthabite_dia);
         $this->j131_dthabite_mes = ($this->j131_dthabite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_dthabite_mes"]:$this->j131_dthabite_mes);
         $this->j131_dthabite_ano = ($this->j131_dthabite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_dthabite_ano"]:$this->j131_dthabite_ano);
         if($this->j131_dthabite_dia != ""){
            $this->j131_dthabite = $this->j131_dthabite_ano."-".$this->j131_dthabite_mes."-".$this->j131_dthabite_dia;
         }
       }
       if($this->j131_data == ""){
         $this->j131_data_dia = ($this->j131_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_data_dia"]:$this->j131_data_dia);
         $this->j131_data_mes = ($this->j131_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_data_mes"]:$this->j131_data_mes);
         $this->j131_data_ano = ($this->j131_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_data_ano"]:$this->j131_data_ano);
         if($this->j131_data_dia != ""){
            $this->j131_data = $this->j131_data_ano."-".$this->j131_data_mes."-".$this->j131_data_dia;
         }
       }
       $this->j131_hora = ($this->j131_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_hora"]:$this->j131_hora);
       $this->j131_obs = ($this->j131_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_obs"]:$this->j131_obs);
     }else{
       $this->j131_sequencial = ($this->j131_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j131_sequencial"]:$this->j131_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j131_sequencial){ 
      $this->atualizacampos();
     if($this->j131_idcons == null ){ 
       $this->erro_sql = " Campo Constru��o nao Informado.";
       $this->erro_campo = "j131_idcons";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j131_matric == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "j131_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j131_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "j131_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j131_dtprot == null ){ 
       $this->erro_sql = " Campo Data do Processo de Protocolo nao Informado.";
       $this->erro_campo = "j131_dtprot_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j131_cadhab == null ){ 
       $this->j131_cadhab = "0";
     }
     if($this->j131_dthabite == null ){ 
       $this->erro_sql = " Campo Data do Habite-se nao Informado.";
       $this->erro_campo = "j131_dthabite_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j131_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "j131_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j131_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "j131_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j131_sequencial == "" || $j131_sequencial == null ){
       $result = db_query("select nextval('iptuconstrhabite_j131_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptuconstrhabite_j131_sequencial_seq do campo: j131_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j131_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptuconstrhabite_j131_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j131_sequencial)){
         $this->erro_sql = " Campo j131_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j131_sequencial = $j131_sequencial; 
       }
     }
     if(($this->j131_sequencial == null) || ($this->j131_sequencial == "") ){ 
       $this->erro_sql = " Campo j131_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptuconstrhabite(
                                       j131_sequencial 
                                      ,j131_idcons 
                                      ,j131_matric 
                                      ,j131_usuario 
                                      ,j131_codprot 
                                      ,j131_dtprot 
                                      ,j131_cadhab 
                                      ,j131_dthabite 
                                      ,j131_data 
                                      ,j131_hora 
                                      ,j131_obs 
                       )
                values (
                                $this->j131_sequencial 
                               ,$this->j131_idcons 
                               ,$this->j131_matric 
                               ,$this->j131_usuario 
                               ,'$this->j131_codprot' 
                               ,".($this->j131_dtprot == "null" || $this->j131_dtprot == ""?"null":"'".$this->j131_dtprot."'")." 
                               ,'$this->j131_cadhab' 
                               ,".($this->j131_dthabite == "null" || $this->j131_dthabite == ""?"null":"'".$this->j131_dthabite."'")." 
                               ,".($this->j131_data == "null" || $this->j131_data == ""?"null":"'".$this->j131_data."'")." 
                               ,'$this->j131_hora' 
                               ,'$this->j131_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Habite-se das constru��es ($this->j131_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Habite-se das constru��es j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Habite-se das constru��es ($this->j131_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j131_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j131_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18438,'$this->j131_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3261,18438,'','".AddSlashes(pg_result($resaco,0,'j131_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18439,'','".AddSlashes(pg_result($resaco,0,'j131_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18440,'','".AddSlashes(pg_result($resaco,0,'j131_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18444,'','".AddSlashes(pg_result($resaco,0,'j131_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18441,'','".AddSlashes(pg_result($resaco,0,'j131_codprot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18541,'','".AddSlashes(pg_result($resaco,0,'j131_dtprot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18442,'','".AddSlashes(pg_result($resaco,0,'j131_cadhab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18540,'','".AddSlashes(pg_result($resaco,0,'j131_dthabite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18443,'','".AddSlashes(pg_result($resaco,0,'j131_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18445,'','".AddSlashes(pg_result($resaco,0,'j131_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3261,18542,'','".AddSlashes(pg_result($resaco,0,'j131_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j131_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptuconstrhabite set ";
     $virgula = "";
     if(trim($this->j131_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_sequencial"])){ 
       $sql  .= $virgula." j131_sequencial = $this->j131_sequencial ";
       $virgula = ",";
       if(trim($this->j131_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j131_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j131_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_idcons"])){ 
       $sql  .= $virgula." j131_idcons = $this->j131_idcons ";
       $virgula = ",";
       if(trim($this->j131_idcons) == null ){ 
         $this->erro_sql = " Campo Constru��o nao Informado.";
         $this->erro_campo = "j131_idcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j131_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_matric"])){ 
       $sql  .= $virgula." j131_matric = $this->j131_matric ";
       $virgula = ",";
       if(trim($this->j131_matric) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "j131_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j131_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_usuario"])){ 
       $sql  .= $virgula." j131_usuario = $this->j131_usuario ";
       $virgula = ",";
       if(trim($this->j131_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "j131_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j131_codprot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_codprot"])){ 
       $sql  .= $virgula." j131_codprot = '$this->j131_codprot' ";
       $virgula = ",";
     }
     if(trim($this->j131_dtprot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_dtprot_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j131_dtprot_dia"] !="") ){ 
       $sql  .= $virgula." j131_dtprot = '$this->j131_dtprot' ";
       $virgula = ",";
       if(trim($this->j131_dtprot) == null ){ 
         $this->erro_sql = " Campo Data do Processo de Protocolo nao Informado.";
         $this->erro_campo = "j131_dtprot_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j131_dtprot_dia"])){ 
         $sql  .= $virgula." j131_dtprot = null ";
         $virgula = ",";
         if(trim($this->j131_dtprot) == null ){ 
           $this->erro_sql = " Campo Data do Processo de Protocolo nao Informado.";
           $this->erro_campo = "j131_dtprot_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j131_cadhab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_cadhab"])){ 
       $sql  .= $virgula." j131_cadhab = '$this->j131_cadhab' ";
       $virgula = ",";
     }
     if(trim($this->j131_dthabite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_dthabite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j131_dthabite_dia"] !="") ){ 
       $sql  .= $virgula." j131_dthabite = '$this->j131_dthabite' ";
       $virgula = ",";
       if(trim($this->j131_dthabite) == null ){ 
         $this->erro_sql = " Campo Data do Habite-se nao Informado.";
         $this->erro_campo = "j131_dthabite_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j131_dthabite_dia"])){ 
         $sql  .= $virgula." j131_dthabite = null ";
         $virgula = ",";
         if(trim($this->j131_dthabite) == null ){ 
           $this->erro_sql = " Campo Data do Habite-se nao Informado.";
           $this->erro_campo = "j131_dthabite_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j131_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j131_data_dia"] !="") ){ 
       $sql  .= $virgula." j131_data = '$this->j131_data' ";
       $virgula = ",";
       if(trim($this->j131_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "j131_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j131_data_dia"])){ 
         $sql  .= $virgula." j131_data = null ";
         $virgula = ",";
         if(trim($this->j131_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "j131_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j131_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_hora"])){ 
       $sql  .= $virgula." j131_hora = '$this->j131_hora' ";
       $virgula = ",";
       if(trim($this->j131_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "j131_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j131_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j131_obs"])){ 
       $sql  .= $virgula." j131_obs = '$this->j131_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j131_sequencial!=null){
       $sql .= " j131_sequencial = $this->j131_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j131_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18438,'$this->j131_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_sequencial"]) || $this->j131_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3261,18438,'".AddSlashes(pg_result($resaco,$conresaco,'j131_sequencial'))."','$this->j131_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_idcons"]) || $this->j131_idcons != "")
           $resac = db_query("insert into db_acount values($acount,3261,18439,'".AddSlashes(pg_result($resaco,$conresaco,'j131_idcons'))."','$this->j131_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_matric"]) || $this->j131_matric != "")
           $resac = db_query("insert into db_acount values($acount,3261,18440,'".AddSlashes(pg_result($resaco,$conresaco,'j131_matric'))."','$this->j131_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_usuario"]) || $this->j131_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3261,18444,'".AddSlashes(pg_result($resaco,$conresaco,'j131_usuario'))."','$this->j131_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_codprot"]) || $this->j131_codprot != "")
           $resac = db_query("insert into db_acount values($acount,3261,18441,'".AddSlashes(pg_result($resaco,$conresaco,'j131_codprot'))."','$this->j131_codprot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_dtprot"]) || $this->j131_dtprot != "")
           $resac = db_query("insert into db_acount values($acount,3261,18541,'".AddSlashes(pg_result($resaco,$conresaco,'j131_dtprot'))."','$this->j131_dtprot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_cadhab"]) || $this->j131_cadhab != "")
           $resac = db_query("insert into db_acount values($acount,3261,18442,'".AddSlashes(pg_result($resaco,$conresaco,'j131_cadhab'))."','$this->j131_cadhab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_dthabite"]) || $this->j131_dthabite != "")
           $resac = db_query("insert into db_acount values($acount,3261,18540,'".AddSlashes(pg_result($resaco,$conresaco,'j131_dthabite'))."','$this->j131_dthabite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_data"]) || $this->j131_data != "")
           $resac = db_query("insert into db_acount values($acount,3261,18443,'".AddSlashes(pg_result($resaco,$conresaco,'j131_data'))."','$this->j131_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_hora"]) || $this->j131_hora != "")
           $resac = db_query("insert into db_acount values($acount,3261,18445,'".AddSlashes(pg_result($resaco,$conresaco,'j131_hora'))."','$this->j131_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j131_obs"]) || $this->j131_obs != "")
           $resac = db_query("insert into db_acount values($acount,3261,18542,'".AddSlashes(pg_result($resaco,$conresaco,'j131_obs'))."','$this->j131_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Habite-se das constru��es nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j131_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Habite-se das constru��es nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j131_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j131_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j131_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j131_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18438,'$j131_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3261,18438,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18439,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18440,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18444,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18441,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_codprot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18541,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_dtprot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18442,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_cadhab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18540,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_dthabite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18443,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18445,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3261,18542,'','".AddSlashes(pg_result($resaco,$iresaco,'j131_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptuconstrhabite
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j131_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j131_sequencial = $j131_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Habite-se das constru��es nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j131_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Habite-se das constru��es nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j131_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j131_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:iptuconstrhabite";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptuconstrhabite ";
     $sql .= "      inner join iptuconstr  on  iptuconstr.j39_matric = iptuconstrhabite.j131_matric and  iptuconstr.j39_idcons = iptuconstrhabite.j131_idcons";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuconstr.j39_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j131_sequencial!=null ){
         $sql2 .= " where iptuconstrhabite.j131_sequencial = $j131_sequencial "; 
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
   function sql_query_file ( $j131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptuconstrhabite ";
     $sql2 = "";
     if($dbwhere==""){
       if($j131_sequencial!=null ){
         $sql2 .= " where iptuconstrhabite.j131_sequencial = $j131_sequencial "; 
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
   function sql_query_dados ( $j131_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptuconstrhabite ";
     $sql .= "      inner join iptuconstr   on iptuconstr.j39_matric    = iptuconstrhabite.j131_matric  "; 
     $sql .= "                             and iptuconstr.j39_idcons    = iptuconstrhabite.j131_idcons  ";
     $sql .= "      inner join ruas         on ruas.j14_codigo          = iptuconstr.j39_codigo         ";
     $sql .= "      inner join iptubase     on iptubase.j01_matric      = iptuconstr.j39_matric         ";
     $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario   = iptuconstrhabite.j131_usuario ";     
     $sql .= "      left join protprocesso  on trim(protprocesso.p58_codproc::text) = trim(iptuconstrhabite.j131_codprot::text) ";
     $sql .= "      left join obrashabite   on trim(obrashabite.ob09_codhab::text)  = trim(iptuconstrhabite.j131_cadhab::text)  ";     
     $sql2 = "";
     if($dbwhere==""){
       if($j131_sequencial!=null ){
         $sql2 .= " where iptuconstrhabite.j131_sequencial = $j131_sequencial "; 
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