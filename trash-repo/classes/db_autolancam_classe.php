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

//MODULO: fiscal
//CLASSE DA ENTIDADE autolancam
class cl_autolancam { 
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
   var $y88_codtermo = 0; 
   var $y88_codlev = 0; 
   var $y88_data_dia = null; 
   var $y88_data_mes = null; 
   var $y88_data_ano = null; 
   var $y88_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y88_codtermo = int4 = Código Termo 
                 y88_codlev = int4 = Levantamento 
                 y88_data = date = Data do auto de lançamento 
                 ";
   //funcao construtor da classe 
   function cl_autolancam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("autolancam"); 
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
       $this->y88_codtermo = ($this->y88_codtermo == ""?@$GLOBALS["HTTP_POST_VARS"]["y88_codtermo"]:$this->y88_codtermo);
       $this->y88_codlev = ($this->y88_codlev == ""?@$GLOBALS["HTTP_POST_VARS"]["y88_codlev"]:$this->y88_codlev);
       if($this->y88_data == ""){
         $this->y88_data_dia = ($this->y88_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y88_data_dia"]:$this->y88_data_dia);
         $this->y88_data_mes = ($this->y88_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y88_data_mes"]:$this->y88_data_mes);
         $this->y88_data_ano = ($this->y88_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y88_data_ano"]:$this->y88_data_ano);
         if($this->y88_data_dia != ""){
            $this->y88_data = $this->y88_data_ano."-".$this->y88_data_mes."-".$this->y88_data_dia;
         }
       }
     }else{
       $this->y88_codtermo = ($this->y88_codtermo == ""?@$GLOBALS["HTTP_POST_VARS"]["y88_codtermo"]:$this->y88_codtermo);
     }
   }
   // funcao para inclusao
   function incluir ($y88_codtermo){ 
      $this->atualizacampos();
     if($this->y88_codlev == null ){ 
       $this->erro_sql = " Campo Levantamento nao Informado.";
       $this->erro_campo = "y88_codlev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y88_data == null ){ 
       $this->erro_sql = " Campo Data do auto de lançamento nao Informado.";
       $this->erro_campo = "y88_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y88_codtermo == "" || $y88_codtermo == null ){
       $result = db_query("select nextval('autolancam_y88_codtermo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: autolancam_y88_codtermo_seq do campo: y88_codtermo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y88_codtermo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from autolancam_y88_codtermo_seq");
       if(($result != false) && (pg_result($result,0,0) < $y88_codtermo)){
         $this->erro_sql = " Campo y88_codtermo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y88_codtermo = $y88_codtermo; 
       }
     }
     if(($this->y88_codtermo == null) || ($this->y88_codtermo == "") ){ 
       $this->erro_sql = " Campo y88_codtermo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into autolancam(
                                       y88_codtermo 
                                      ,y88_codlev 
                                      ,y88_data 
                       )
                values (
                                $this->y88_codtermo 
                               ,$this->y88_codlev 
                               ,".($this->y88_data == "null" || $this->y88_data == ""?"null":"'".$this->y88_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Auto de lancamento ($this->y88_codtermo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Auto de lancamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Auto de lancamento ($this->y88_codtermo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y88_codtermo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y88_codtermo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7338,'$this->y88_codtermo','I')");
       $resac = db_query("insert into db_acount values($acount,1219,7338,'','".AddSlashes(pg_result($resaco,0,'y88_codtermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1219,7340,'','".AddSlashes(pg_result($resaco,0,'y88_codlev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1219,7339,'','".AddSlashes(pg_result($resaco,0,'y88_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y88_codtermo=null) { 
      $this->atualizacampos();
     $sql = " update autolancam set ";
     $virgula = "";
     if(trim($this->y88_codtermo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y88_codtermo"])){ 
       $sql  .= $virgula." y88_codtermo = $this->y88_codtermo ";
       $virgula = ",";
       if(trim($this->y88_codtermo) == null ){ 
         $this->erro_sql = " Campo Código Termo nao Informado.";
         $this->erro_campo = "y88_codtermo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y88_codlev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y88_codlev"])){ 
       $sql  .= $virgula." y88_codlev = $this->y88_codlev ";
       $virgula = ",";
       if(trim($this->y88_codlev) == null ){ 
         $this->erro_sql = " Campo Levantamento nao Informado.";
         $this->erro_campo = "y88_codlev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y88_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y88_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y88_data_dia"] !="") ){ 
       $sql  .= $virgula." y88_data = '$this->y88_data' ";
       $virgula = ",";
       if(trim($this->y88_data) == null ){ 
         $this->erro_sql = " Campo Data do auto de lançamento nao Informado.";
         $this->erro_campo = "y88_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y88_data_dia"])){ 
         $sql  .= $virgula." y88_data = null ";
         $virgula = ",";
         if(trim($this->y88_data) == null ){ 
           $this->erro_sql = " Campo Data do auto de lançamento nao Informado.";
           $this->erro_campo = "y88_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($y88_codtermo!=null){
       $sql .= " y88_codtermo = $this->y88_codtermo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y88_codtermo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7338,'$this->y88_codtermo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y88_codtermo"]))
           $resac = db_query("insert into db_acount values($acount,1219,7338,'".AddSlashes(pg_result($resaco,$conresaco,'y88_codtermo'))."','$this->y88_codtermo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y88_codlev"]))
           $resac = db_query("insert into db_acount values($acount,1219,7340,'".AddSlashes(pg_result($resaco,$conresaco,'y88_codlev'))."','$this->y88_codlev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y88_data"]))
           $resac = db_query("insert into db_acount values($acount,1219,7339,'".AddSlashes(pg_result($resaco,$conresaco,'y88_data'))."','$this->y88_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Auto de lancamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y88_codtermo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Auto de lancamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y88_codtermo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y88_codtermo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y88_codtermo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y88_codtermo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7338,'$y88_codtermo','E')");
         $resac = db_query("insert into db_acount values($acount,1219,7338,'','".AddSlashes(pg_result($resaco,$iresaco,'y88_codtermo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1219,7340,'','".AddSlashes(pg_result($resaco,$iresaco,'y88_codlev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1219,7339,'','".AddSlashes(pg_result($resaco,$iresaco,'y88_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from autolancam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y88_codtermo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y88_codtermo = $y88_codtermo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Auto de lancamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y88_codtermo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Auto de lancamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y88_codtermo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y88_codtermo;
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
        $this->erro_sql   = "Record Vazio na Tabela:autolancam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>