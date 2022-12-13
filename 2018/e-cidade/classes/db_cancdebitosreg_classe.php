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

//MODULO: caixa
//CLASSE DA ENTIDADE cancdebitosreg
class cl_cancdebitosreg { 
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
   var $k21_sequencia = 0; 
   var $k21_codigo = 0; 
   var $k21_numpre = 0; 
   var $k21_numpar = 0; 
   var $k21_receit = 0; 
   var $k21_data_dia = null; 
   var $k21_data_mes = null; 
   var $k21_data_ano = null; 
   var $k21_data = null; 
   var $k21_hora = null; 
   var $k21_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k21_sequencia = int8 = Sequencia 
                 k21_codigo = int8 = Código 
                 k21_numpre = int4 = Numpre 
                 k21_numpar = int4 = Parcela 
                 k21_receit = int4 = Receita 
                 k21_data = date = Data 
                 k21_hora = varchar(5) = Hora 
                 k21_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_cancdebitosreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cancdebitosreg"); 
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
       $this->k21_sequencia = ($this->k21_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_sequencia"]:$this->k21_sequencia);
       $this->k21_codigo = ($this->k21_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_codigo"]:$this->k21_codigo);
       $this->k21_numpre = ($this->k21_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_numpre"]:$this->k21_numpre);
       $this->k21_numpar = ($this->k21_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_numpar"]:$this->k21_numpar);
       $this->k21_receit = ($this->k21_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_receit"]:$this->k21_receit);
       if($this->k21_data == ""){
         $this->k21_data_dia = ($this->k21_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_data_dia"]:$this->k21_data_dia);
         $this->k21_data_mes = ($this->k21_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_data_mes"]:$this->k21_data_mes);
         $this->k21_data_ano = ($this->k21_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_data_ano"]:$this->k21_data_ano);
         if($this->k21_data_dia != ""){
            $this->k21_data = $this->k21_data_ano."-".$this->k21_data_mes."-".$this->k21_data_dia;
         }
       }
       $this->k21_hora = ($this->k21_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_hora"]:$this->k21_hora);
       $this->k21_obs = ($this->k21_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_obs"]:$this->k21_obs);
     }else{
       $this->k21_sequencia = ($this->k21_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["k21_sequencia"]:$this->k21_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($k21_sequencia){ 
      $this->atualizacampos();
     if($this->k21_codigo == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "k21_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k21_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k21_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k21_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k21_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k21_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "k21_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k21_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k21_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k21_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k21_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k21_sequencia == "" || $k21_sequencia == null ){
       $result = db_query("select nextval('cancdebitosreg_k21_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cancdebitosreg_k21_sequencia_seq do campo: k21_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k21_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cancdebitosreg_k21_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $k21_sequencia)){
         $this->erro_sql = " Campo k21_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k21_sequencia = $k21_sequencia; 
       }
     }
     if(($this->k21_sequencia == null) || ($this->k21_sequencia == "") ){ 
       $this->erro_sql = " Campo k21_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cancdebitosreg(
                                       k21_sequencia 
                                      ,k21_codigo 
                                      ,k21_numpre 
                                      ,k21_numpar 
                                      ,k21_receit 
                                      ,k21_data 
                                      ,k21_hora 
                                      ,k21_obs 
                       )
                values (
                                $this->k21_sequencia 
                               ,$this->k21_codigo 
                               ,$this->k21_numpre 
                               ,$this->k21_numpar 
                               ,$this->k21_receit 
                               ,".($this->k21_data == "null" || $this->k21_data == ""?"null":"'".$this->k21_data."'")." 
                               ,'$this->k21_hora' 
                               ,'$this->k21_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros dos débitos a cancelar ($this->k21_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros dos débitos a cancelar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros dos débitos a cancelar ($this->k21_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k21_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k21_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7330,'$this->k21_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1218,7330,'','".AddSlashes(pg_result($resaco,0,'k21_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1218,7331,'','".AddSlashes(pg_result($resaco,0,'k21_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1218,7332,'','".AddSlashes(pg_result($resaco,0,'k21_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1218,7333,'','".AddSlashes(pg_result($resaco,0,'k21_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1218,7334,'','".AddSlashes(pg_result($resaco,0,'k21_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1218,7335,'','".AddSlashes(pg_result($resaco,0,'k21_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1218,7336,'','".AddSlashes(pg_result($resaco,0,'k21_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1218,7337,'','".AddSlashes(pg_result($resaco,0,'k21_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k21_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update cancdebitosreg set ";
     $virgula = "";
     if(trim($this->k21_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k21_sequencia"])){ 
       $sql  .= $virgula." k21_sequencia = $this->k21_sequencia ";
       $virgula = ",";
       if(trim($this->k21_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "k21_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k21_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k21_codigo"])){ 
       $sql  .= $virgula." k21_codigo = $this->k21_codigo ";
       $virgula = ",";
       if(trim($this->k21_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k21_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k21_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k21_numpre"])){ 
       $sql  .= $virgula." k21_numpre = $this->k21_numpre ";
       $virgula = ",";
       if(trim($this->k21_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k21_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k21_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k21_numpar"])){ 
       $sql  .= $virgula." k21_numpar = $this->k21_numpar ";
       $virgula = ",";
       if(trim($this->k21_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k21_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k21_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k21_receit"])){ 
       $sql  .= $virgula." k21_receit = $this->k21_receit ";
       $virgula = ",";
       if(trim($this->k21_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k21_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k21_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k21_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k21_data_dia"] !="") ){ 
       $sql  .= $virgula." k21_data = '$this->k21_data' ";
       $virgula = ",";
       if(trim($this->k21_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k21_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k21_data_dia"])){ 
         $sql  .= $virgula." k21_data = null ";
         $virgula = ",";
         if(trim($this->k21_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k21_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k21_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k21_hora"])){ 
       $sql  .= $virgula." k21_hora = '$this->k21_hora' ";
       $virgula = ",";
       if(trim($this->k21_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k21_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k21_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k21_obs"])){ 
       $sql  .= $virgula." k21_obs = '$this->k21_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k21_sequencia!=null){
       $sql .= " k21_sequencia = $this->k21_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k21_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7330,'$this->k21_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k21_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1218,7330,'".AddSlashes(pg_result($resaco,$conresaco,'k21_sequencia'))."','$this->k21_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k21_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1218,7331,'".AddSlashes(pg_result($resaco,$conresaco,'k21_codigo'))."','$this->k21_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k21_numpre"]))
           $resac = db_query("insert into db_acount values($acount,1218,7332,'".AddSlashes(pg_result($resaco,$conresaco,'k21_numpre'))."','$this->k21_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k21_numpar"]))
           $resac = db_query("insert into db_acount values($acount,1218,7333,'".AddSlashes(pg_result($resaco,$conresaco,'k21_numpar'))."','$this->k21_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k21_receit"]))
           $resac = db_query("insert into db_acount values($acount,1218,7334,'".AddSlashes(pg_result($resaco,$conresaco,'k21_receit'))."','$this->k21_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k21_data"]))
           $resac = db_query("insert into db_acount values($acount,1218,7335,'".AddSlashes(pg_result($resaco,$conresaco,'k21_data'))."','$this->k21_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k21_hora"]))
           $resac = db_query("insert into db_acount values($acount,1218,7336,'".AddSlashes(pg_result($resaco,$conresaco,'k21_hora'))."','$this->k21_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k21_obs"]))
           $resac = db_query("insert into db_acount values($acount,1218,7337,'".AddSlashes(pg_result($resaco,$conresaco,'k21_obs'))."','$this->k21_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros dos débitos a cancelar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k21_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros dos débitos a cancelar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k21_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k21_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k21_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k21_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7330,'$k21_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1218,7330,'','".AddSlashes(pg_result($resaco,$iresaco,'k21_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1218,7331,'','".AddSlashes(pg_result($resaco,$iresaco,'k21_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1218,7332,'','".AddSlashes(pg_result($resaco,$iresaco,'k21_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1218,7333,'','".AddSlashes(pg_result($resaco,$iresaco,'k21_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1218,7334,'','".AddSlashes(pg_result($resaco,$iresaco,'k21_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1218,7335,'','".AddSlashes(pg_result($resaco,$iresaco,'k21_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1218,7336,'','".AddSlashes(pg_result($resaco,$iresaco,'k21_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1218,7337,'','".AddSlashes(pg_result($resaco,$iresaco,'k21_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cancdebitosreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k21_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k21_sequencia = $k21_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros dos débitos a cancelar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k21_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros dos débitos a cancelar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k21_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k21_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:cancdebitosreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k21_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosreg ";
     $sql .= "      inner join cancdebitos   on  cancdebitos.k20_codigo = cancdebitosreg.k21_codigo";
     $sql .= "                              and  cancdebitos.k20_instit = ".db_getsession("DB_instit");
     $sql2 = "";
     if($dbwhere==""){
       if($k21_sequencia!=null ){
         $sql2 .= " where cancdebitosreg.k21_sequencia = $k21_sequencia "; 
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
   function sql_query_file ( $k21_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($k21_sequencia!=null ){
         $sql2 .= " where cancdebitosreg.k21_sequencia = $k21_sequencia "; 
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
  
   function sql_query_susp( $k21_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosreg ";
     $sql .= "     inner join cancdebitossusp    on cancdebitossusp.ar21_cancdebitos 	  = cancdebitosreg.k21_codigo    ";
     $sql .= "     inner join suspensaofinaliza  on suspensaofinaliza.ar19_sequencial	  = cancdebitossusp.ar21_suspensaofinaliza";
     $sql .= "     inner join cancdebitosprocreg on cancdebitosprocreg.k24_cancdebitosreg = cancdebitosreg.k21_sequencia ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($k21_sequencia!=null ){
         $sql2 .= " where cancdebitosreg.k21_sequencia = $k21_sequencia "; 
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