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

//MODULO: saude
//CLASSE DA ENTIDADE vacinasaplicadas
class cl_vacinasaplicadas { 
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
   var $sd08_c_vacina = null; 
   var $sd08_i_unidade = 0; 
   var $sd08_i_cgm = 0; 
   var $sd08_d_data_dia = null; 
   var $sd08_d_data_mes = null; 
   var $sd08_d_data_ano = null; 
   var $sd08_d_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd08_c_vacina = char(10) = Vacina 
                 sd08_i_unidade = int4 = Unidade 
                 sd08_i_cgm = int4 = Cgm 
                 sd08_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_vacinasaplicadas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vacinasaplicadas"); 
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
       $this->sd08_c_vacina = ($this->sd08_c_vacina == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_c_vacina"]:$this->sd08_c_vacina);
       $this->sd08_i_unidade = ($this->sd08_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_i_unidade"]:$this->sd08_i_unidade);
       $this->sd08_i_cgm = ($this->sd08_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_i_cgm"]:$this->sd08_i_cgm);
       if($this->sd08_d_data == ""){
         $this->sd08_d_data_dia = ($this->sd08_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_d_data_dia"]:$this->sd08_d_data_dia);
         $this->sd08_d_data_mes = ($this->sd08_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_d_data_mes"]:$this->sd08_d_data_mes);
         $this->sd08_d_data_ano = ($this->sd08_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_d_data_ano"]:$this->sd08_d_data_ano);
         if($this->sd08_d_data_dia != ""){
            $this->sd08_d_data = $this->sd08_d_data_ano."-".$this->sd08_d_data_mes."-".$this->sd08_d_data_dia;
         }
       }
     }else{
       $this->sd08_c_vacina = ($this->sd08_c_vacina == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_c_vacina"]:$this->sd08_c_vacina);
       $this->sd08_i_unidade = ($this->sd08_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_i_unidade"]:$this->sd08_i_unidade);
       $this->sd08_i_cgm = ($this->sd08_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_i_cgm"]:$this->sd08_i_cgm);
       $this->sd08_d_data = ($this->sd08_d_data == ""?@$GLOBALS["HTTP_POST_VARS"]["sd08_d_data_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["sd08_d_data_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["sd08_d_data_dia"]:$this->sd08_d_data);
     }
   }
   // funcao para inclusao
   function incluir ($sd08_c_vacina,$sd08_i_unidade,$sd08_i_cgm,$sd08_d_data){ 
      $this->atualizacampos();
       $this->sd08_c_vacina = $sd08_c_vacina; 
       $this->sd08_i_unidade = $sd08_i_unidade; 
       $this->sd08_i_cgm = $sd08_i_cgm; 
       $this->sd08_d_data = $sd08_d_data; 
     if(($this->sd08_c_vacina == null) || ($this->sd08_c_vacina == "") ){ 
       $this->erro_sql = " Campo sd08_c_vacina nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->sd08_i_unidade == null) || ($this->sd08_i_unidade == "") ){ 
       $this->erro_sql = " Campo sd08_i_unidade nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->sd08_i_cgm == null) || ($this->sd08_i_cgm == "") ){ 
       $this->erro_sql = " Campo sd08_i_cgm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->sd08_d_data == null) || ($this->sd08_d_data == "") ){ 
       $this->erro_sql = " Campo sd08_d_data nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vacinasaplicadas(
                                       sd08_c_vacina 
                                      ,sd08_i_unidade 
                                      ,sd08_i_cgm 
                                      ,sd08_d_data 
                       )
                values (
                                '$this->sd08_c_vacina' 
                               ,$this->sd08_i_unidade 
                               ,$this->sd08_i_cgm 
                               ,".($this->sd08_d_data == "null" || $this->sd08_d_data == ""?"null":"'".$this->sd08_d_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vacinas Aplicadas ($this->sd08_c_vacina."-".$this->sd08_i_unidade."-".$this->sd08_i_cgm."-".$this->sd08_d_data) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vacinas Aplicadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vacinas Aplicadas ($this->sd08_c_vacina."-".$this->sd08_i_unidade."-".$this->sd08_i_cgm."-".$this->sd08_d_data) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd08_c_vacina."-".$this->sd08_i_unidade."-".$this->sd08_i_cgm."-".$this->sd08_d_data;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd08_c_vacina,$this->sd08_i_unidade,$this->sd08_i_cgm,$this->sd08_d_data));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,100115,'$this->sd08_c_vacina','I')");
       $resac = db_query("insert into db_acountkey values($acount,100114,'$this->sd08_i_unidade','I')");
       $resac = db_query("insert into db_acountkey values($acount,100113,'$this->sd08_i_cgm','I')");
       $resac = db_query("insert into db_acountkey values($acount,100112,'$this->sd08_d_data','I')");
       $resac = db_query("insert into db_acount values($acount,100020,100115,'','".AddSlashes(pg_result($resaco,0,'sd08_c_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100020,100114,'','".AddSlashes(pg_result($resaco,0,'sd08_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100020,100113,'','".AddSlashes(pg_result($resaco,0,'sd08_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100020,100112,'','".AddSlashes(pg_result($resaco,0,'sd08_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd08_c_vacina=null,$sd08_i_unidade=null,$sd08_i_cgm=null,$sd08_d_data=null) { 
      $this->atualizacampos();
     $sql = " update vacinasaplicadas set ";
     $virgula = "";
     if(trim($this->sd08_c_vacina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd08_c_vacina"])){ 
       $sql  .= $virgula." sd08_c_vacina = '$this->sd08_c_vacina' ";
       $virgula = ",";
       if(trim($this->sd08_c_vacina) == null ){ 
         $this->erro_sql = " Campo Vacina nao Informado.";
         $this->erro_campo = "sd08_c_vacina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd08_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd08_i_unidade"])){ 
       $sql  .= $virgula." sd08_i_unidade = $this->sd08_i_unidade ";
       $virgula = ",";
       if(trim($this->sd08_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "sd08_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd08_i_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd08_i_cgm"])){ 
       $sql  .= $virgula." sd08_i_cgm = $this->sd08_i_cgm ";
       $virgula = ",";
       if(trim($this->sd08_i_cgm) == null ){ 
         $this->erro_sql = " Campo Cgm nao Informado.";
         $this->erro_campo = "sd08_i_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd08_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd08_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd08_d_data_dia"] !="") ){ 
       $sql  .= $virgula." sd08_d_data = '$this->sd08_d_data' ";
       $virgula = ",";
       if(trim($this->sd08_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "sd08_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd08_d_data_dia"])){ 
         $sql  .= $virgula." sd08_d_data = null ";
         $virgula = ",";
         if(trim($this->sd08_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "sd08_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($sd08_c_vacina!=null){
       $sql .= " sd08_c_vacina = '$this->sd08_c_vacina'";
     }
     if($sd08_i_unidade!=null){
       $sql .= " and  sd08_i_unidade = $this->sd08_i_unidade";
     }
     if($sd08_i_cgm!=null){
       $sql .= " and  sd08_i_cgm = $this->sd08_i_cgm";
     }
     if($sd08_d_data!=null){
       $sql .= " and  sd08_d_data = '$this->sd08_d_data'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd08_c_vacina,$this->sd08_i_unidade,$this->sd08_i_cgm,$this->sd08_d_data));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100115,'$this->sd08_c_vacina','A')");
         $resac = db_query("insert into db_acountkey values($acount,100114,'$this->sd08_i_unidade','A')");
         $resac = db_query("insert into db_acountkey values($acount,100113,'$this->sd08_i_cgm','A')");
         $resac = db_query("insert into db_acountkey values($acount,100112,'$this->sd08_d_data','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd08_c_vacina"]))
           $resac = db_query("insert into db_acount values($acount,100020,100115,'".AddSlashes(pg_result($resaco,$conresaco,'sd08_c_vacina'))."','$this->sd08_c_vacina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd08_i_unidade"]))
           $resac = db_query("insert into db_acount values($acount,100020,100114,'".AddSlashes(pg_result($resaco,$conresaco,'sd08_i_unidade'))."','$this->sd08_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd08_i_cgm"]))
           $resac = db_query("insert into db_acount values($acount,100020,100113,'".AddSlashes(pg_result($resaco,$conresaco,'sd08_i_cgm'))."','$this->sd08_i_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd08_d_data"]))
           $resac = db_query("insert into db_acount values($acount,100020,100112,'".AddSlashes(pg_result($resaco,$conresaco,'sd08_d_data'))."','$this->sd08_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vacinas Aplicadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd08_c_vacina."-".$this->sd08_i_unidade."-".$this->sd08_i_cgm."-".$this->sd08_d_data;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vacinas Aplicadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd08_c_vacina."-".$this->sd08_i_unidade."-".$this->sd08_i_cgm."-".$this->sd08_d_data;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd08_c_vacina."-".$this->sd08_i_unidade."-".$this->sd08_i_cgm."-".$this->sd08_d_data;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd08_c_vacina=null,$sd08_i_unidade=null,$sd08_i_cgm=null,$sd08_d_data=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd08_c_vacina,$sd08_i_unidade,$sd08_i_cgm,$sd08_d_data));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,100115,'$sd08_c_vacina','E')");
         $resac = db_query("insert into db_acountkey values($acount,100114,'$sd08_i_unidade','E')");
         $resac = db_query("insert into db_acountkey values($acount,100113,'$sd08_i_cgm','E')");
         $resac = db_query("insert into db_acountkey values($acount,100112,'$sd08_d_data','E')");
         $resac = db_query("insert into db_acount values($acount,100020,100115,'','".AddSlashes(pg_result($resaco,$iresaco,'sd08_c_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100020,100114,'','".AddSlashes(pg_result($resaco,$iresaco,'sd08_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100020,100113,'','".AddSlashes(pg_result($resaco,$iresaco,'sd08_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100020,100112,'','".AddSlashes(pg_result($resaco,$iresaco,'sd08_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vacinasaplicadas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd08_c_vacina != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd08_c_vacina = '$sd08_c_vacina' ";
        }
        if($sd08_i_unidade != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd08_i_unidade = $sd08_i_unidade ";
        }
        if($sd08_i_cgm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd08_i_cgm = $sd08_i_cgm ";
        }
        if($sd08_d_data != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd08_d_data = '$sd08_d_data' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vacinas Aplicadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd08_c_vacina."-".$sd08_i_unidade."-".$sd08_i_cgm."-".$sd08_d_data;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vacinas Aplicadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd08_c_vacina."-".$sd08_i_unidade."-".$sd08_i_cgm."-".$sd08_d_data;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd08_c_vacina."-".$sd08_i_unidade."-".$sd08_i_cgm."-".$sd08_d_data;
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
        $this->erro_sql   = "Record Vazio na Tabela:vacinasaplicadas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd08_c_vacina=null,$sd08_i_unidade=null,$sd08_i_cgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vacinasaplicadas ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = vacinasaplicadas.sd08_i_cgm";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = vacinasaplicadas.sd08_i_unidade";
     $sql .= "      inner join vacinas  on  vacinas.sd07_c_codigo = vacinasaplicadas.sd08_c_vacina";
     $sql2 = "";
     if($dbwhere==""){
       if($sd08_c_vacina!=null ){
         $sql2 .= " where vacinasaplicadas.sd08_c_vacina = '$sd08_c_vacina' "; 
       } 
       if($sd08_i_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vacinasaplicadas.sd08_i_unidade = $sd08_i_unidade "; 
       } 
       if($sd08_i_cgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vacinasaplicadas.sd08_i_cgm = $sd08_i_cgm "; 
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
   function sql_query_file ( $sd08_c_vacina=null,$sd08_i_unidade=null,$sd08_i_cgm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vacinasaplicadas ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd08_c_vacina!=null ){
         $sql2 .= " where vacinasaplicadas.sd08_c_vacina = '$sd08_c_vacina' "; 
       } 
       if($sd08_i_unidade!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vacinasaplicadas.sd08_i_unidade = $sd08_i_unidade "; 
       } 
       if($sd08_i_cgm!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vacinasaplicadas.sd08_i_cgm = $sd08_i_cgm "; 
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