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

//MODULO: protocolo
//CLASSE DA ENTIDADE cgmsaude
class cl_cgmsaude { 
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
   var $z01_numcgm = 0; 
   var $z01_tiposangue = 0; 
   var $z01_fatorrh = 0; 
   var $z01_cartaosus = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z01_numcgm = int4 = Numcgm 
                 z01_tiposangue = int4 = Tipo Sanguineo 
                 z01_fatorrh = int4 = Fator RH 
                 z01_cartaosus = varchar(15) = Cart�o SUS 
                 ";
   //funcao construtor da classe 
   function cl_cgmsaude() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgmsaude"); 
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
       $this->z01_numcgm = ($this->z01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_numcgm"]:$this->z01_numcgm);
       $this->z01_tiposangue = ($this->z01_tiposangue == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_tiposangue"]:$this->z01_tiposangue);
       $this->z01_fatorrh = ($this->z01_fatorrh == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_fatorrh"]:$this->z01_fatorrh);
       $this->z01_cartaosus = ($this->z01_cartaosus == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_cartaosus"]:$this->z01_cartaosus);
     }else{
       $this->z01_numcgm = ($this->z01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_numcgm"]:$this->z01_numcgm);
     }
   }
   // funcao para inclusao
   function incluir ($z01_numcgm){ 
      $this->atualizacampos();
     if($this->z01_tiposangue == null ){ 
       $this->erro_sql = " Campo Tipo Sanguineo nao Informado.";
       $this->erro_campo = "z01_tiposangue";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_fatorrh == null ){ 
       $this->erro_sql = " Campo Fator RH nao Informado.";
       $this->erro_campo = "z01_fatorrh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z01_cartaosus == null ){ 
       $this->erro_sql = " Campo Cart�o SUS nao Informado.";
       $this->erro_campo = "z01_cartaosus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->z01_numcgm = $z01_numcgm; 
     if(($this->z01_numcgm == null) || ($this->z01_numcgm == "") ){ 
       $this->erro_sql = " Campo z01_numcgm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmsaude(
                                       z01_numcgm 
                                      ,z01_tiposangue 
                                      ,z01_fatorrh 
                                      ,z01_cartaosus 
                       )
                values (
                                $this->z01_numcgm 
                               ,$this->z01_tiposangue 
                               ,$this->z01_fatorrh 
                               ,'$this->z01_cartaosus' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cgmsaude ($this->z01_numcgm) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cgmsaude j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cgmsaude ($this->z01_numcgm) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z01_numcgm;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->z01_numcgm));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,216,'$this->z01_numcgm','I')");
       $resac = db_query("insert into db_acount values($acount,1639,216,'','".AddSlashes(pg_result($resaco,0,'z01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1639,9560,'','".AddSlashes(pg_result($resaco,0,'z01_tiposangue'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1639,9559,'','".AddSlashes(pg_result($resaco,0,'z01_fatorrh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1639,9561,'','".AddSlashes(pg_result($resaco,0,'z01_cartaosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($z01_numcgm=null) { 
      $this->atualizacampos();
     $sql = " update cgmsaude set ";
     $virgula = "";
     if(trim($this->z01_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_numcgm"])){ 
       $sql  .= $virgula." z01_numcgm = $this->z01_numcgm ";
       $virgula = ",";
       if(trim($this->z01_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "z01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_tiposangue)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_tiposangue"])){ 
       $sql  .= $virgula." z01_tiposangue = $this->z01_tiposangue ";
       $virgula = ",";
       if(trim($this->z01_tiposangue) == null ){ 
         $this->erro_sql = " Campo Tipo Sanguineo nao Informado.";
         $this->erro_campo = "z01_tiposangue";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_fatorrh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_fatorrh"])){ 
       $sql  .= $virgula." z01_fatorrh = $this->z01_fatorrh ";
       $virgula = ",";
       if(trim($this->z01_fatorrh) == null ){ 
         $this->erro_sql = " Campo Fator RH nao Informado.";
         $this->erro_campo = "z01_fatorrh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_cartaosus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_cartaosus"])){ 
       $sql  .= $virgula." z01_cartaosus = '$this->z01_cartaosus' ";
       $virgula = ",";
       if(trim($this->z01_cartaosus) == null ){ 
         $this->erro_sql = " Campo Cart�o SUS nao Informado.";
         $this->erro_campo = "z01_cartaosus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($z01_numcgm!=null){
       $sql .= " z01_numcgm = $this->z01_numcgm";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->z01_numcgm));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,216,'$this->z01_numcgm','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1639,216,'".AddSlashes(pg_result($resaco,$conresaco,'z01_numcgm'))."','$this->z01_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_tiposangue"]))
           $resac = db_query("insert into db_acount values($acount,1639,9560,'".AddSlashes(pg_result($resaco,$conresaco,'z01_tiposangue'))."','$this->z01_tiposangue',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_fatorrh"]))
           $resac = db_query("insert into db_acount values($acount,1639,9559,'".AddSlashes(pg_result($resaco,$conresaco,'z01_fatorrh'))."','$this->z01_fatorrh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["z01_cartaosus"]))
           $resac = db_query("insert into db_acount values($acount,1639,9561,'".AddSlashes(pg_result($resaco,$conresaco,'z01_cartaosus'))."','$this->z01_cartaosus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgmsaude nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_numcgm;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cgmsaude nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_numcgm;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->z01_numcgm;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($z01_numcgm=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($z01_numcgm));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,216,'$z01_numcgm','E')");
         $resac = db_query("insert into db_acount values($acount,1639,216,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1639,9560,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_tiposangue'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1639,9559,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_fatorrh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1639,9561,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_cartaosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgmsaude
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($z01_numcgm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " z01_numcgm = $z01_numcgm ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgmsaude nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z01_numcgm;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cgmsaude nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z01_numcgm;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$z01_numcgm;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmsaude";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>