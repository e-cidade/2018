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
//CLASSE DA ENTIDADE iptutaxamatric
class cl_iptutaxamatric { 
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
   var $j09_iptutaxamatric = 0; 
   var $j09_iptucadtaxaexe = 0; 
   var $j09_matric = 0; 
   var $j09_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j09_iptutaxamatric = int4 = Codigo 
                 j09_iptucadtaxaexe = int8 = Taxa 
                 j09_matric = int4 = Matrícula do Imóvel 
                 j09_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_iptutaxamatric() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptutaxamatric"); 
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
       $this->j09_iptutaxamatric = ($this->j09_iptutaxamatric == ""?@$GLOBALS["HTTP_POST_VARS"]["j09_iptutaxamatric"]:$this->j09_iptutaxamatric);
       $this->j09_iptucadtaxaexe = ($this->j09_iptucadtaxaexe == ""?@$GLOBALS["HTTP_POST_VARS"]["j09_iptucadtaxaexe"]:$this->j09_iptucadtaxaexe);
       $this->j09_matric = ($this->j09_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j09_matric"]:$this->j09_matric);
       $this->j09_valor = ($this->j09_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j09_valor"]:$this->j09_valor);
     }else{
       $this->j09_iptutaxamatric = ($this->j09_iptutaxamatric == ""?@$GLOBALS["HTTP_POST_VARS"]["j09_iptutaxamatric"]:$this->j09_iptutaxamatric);
     }
   }
   // funcao para inclusao
   function incluir ($j09_iptutaxamatric){ 
      $this->atualizacampos();
     if($this->j09_iptucadtaxaexe == null ){ 
       $this->erro_sql = " Campo Taxa nao Informado.";
       $this->erro_campo = "j09_iptucadtaxaexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j09_matric == null ){ 
       $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
       $this->erro_campo = "j09_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j09_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "j09_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j09_iptutaxamatric == "" || $j09_iptutaxamatric == null ){
       $result = db_query("select nextval('iptutaxamatric_j09_iptutaxamatric_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptutaxamatric_j09_iptutaxamatric_seq do campo: j09_iptutaxamatric"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j09_iptutaxamatric = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptutaxamatric_j09_iptutaxamatric_seq");
       if(($result != false) && (pg_result($result,0,0) < $j09_iptutaxamatric)){
         $this->erro_sql = " Campo j09_iptutaxamatric maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j09_iptutaxamatric = $j09_iptutaxamatric; 
       }
     }
     if(($this->j09_iptutaxamatric == null) || ($this->j09_iptutaxamatric == "") ){ 
       $this->erro_sql = " Campo j09_iptutaxamatric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptutaxamatric(
                                       j09_iptutaxamatric 
                                      ,j09_iptucadtaxaexe 
                                      ,j09_matric 
                                      ,j09_valor 
                       )
                values (
                                $this->j09_iptutaxamatric 
                               ,$this->j09_iptucadtaxaexe 
                               ,$this->j09_matric 
                               ,$this->j09_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "iptutaxamatric ($this->j09_iptutaxamatric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "iptutaxamatric já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "iptutaxamatric ($this->j09_iptutaxamatric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j09_iptutaxamatric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j09_iptutaxamatric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9494,'$this->j09_iptutaxamatric','I')");
       $resac = db_query("insert into db_acount values($acount,1630,9494,'','".AddSlashes(pg_result($resaco,0,'j09_iptutaxamatric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1630,11061,'','".AddSlashes(pg_result($resaco,0,'j09_iptucadtaxaexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1630,9495,'','".AddSlashes(pg_result($resaco,0,'j09_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1630,11025,'','".AddSlashes(pg_result($resaco,0,'j09_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j09_iptutaxamatric=null) { 
      $this->atualizacampos();
     $sql = " update iptutaxamatric set ";
     $virgula = "";
     if(trim($this->j09_iptutaxamatric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j09_iptutaxamatric"])){ 
       $sql  .= $virgula." j09_iptutaxamatric = $this->j09_iptutaxamatric ";
       $virgula = ",";
       if(trim($this->j09_iptutaxamatric) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "j09_iptutaxamatric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j09_iptucadtaxaexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j09_iptucadtaxaexe"])){ 
       $sql  .= $virgula." j09_iptucadtaxaexe = $this->j09_iptucadtaxaexe ";
       $virgula = ",";
       if(trim($this->j09_iptucadtaxaexe) == null ){ 
         $this->erro_sql = " Campo Taxa nao Informado.";
         $this->erro_campo = "j09_iptucadtaxaexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j09_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j09_matric"])){ 
       $sql  .= $virgula." j09_matric = $this->j09_matric ";
       $virgula = ",";
       if(trim($this->j09_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula do Imóvel nao Informado.";
         $this->erro_campo = "j09_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j09_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j09_valor"])){ 
       $sql  .= $virgula." j09_valor = $this->j09_valor ";
       $virgula = ",";
       if(trim($this->j09_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "j09_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j09_iptutaxamatric!=null){
       $sql .= " j09_iptutaxamatric = $this->j09_iptutaxamatric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j09_iptutaxamatric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9494,'$this->j09_iptutaxamatric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j09_iptutaxamatric"]))
           $resac = db_query("insert into db_acount values($acount,1630,9494,'".AddSlashes(pg_result($resaco,$conresaco,'j09_iptutaxamatric'))."','$this->j09_iptutaxamatric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j09_iptucadtaxaexe"]))
           $resac = db_query("insert into db_acount values($acount,1630,11061,'".AddSlashes(pg_result($resaco,$conresaco,'j09_iptucadtaxaexe'))."','$this->j09_iptucadtaxaexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j09_matric"]))
           $resac = db_query("insert into db_acount values($acount,1630,9495,'".AddSlashes(pg_result($resaco,$conresaco,'j09_matric'))."','$this->j09_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j09_valor"]))
           $resac = db_query("insert into db_acount values($acount,1630,11025,'".AddSlashes(pg_result($resaco,$conresaco,'j09_valor'))."','$this->j09_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptutaxamatric nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j09_iptutaxamatric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptutaxamatric nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j09_iptutaxamatric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j09_iptutaxamatric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j09_iptutaxamatric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j09_iptutaxamatric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9494,'$j09_iptutaxamatric','E')");
         $resac = db_query("insert into db_acount values($acount,1630,9494,'','".AddSlashes(pg_result($resaco,$iresaco,'j09_iptutaxamatric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1630,11061,'','".AddSlashes(pg_result($resaco,$iresaco,'j09_iptucadtaxaexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1630,9495,'','".AddSlashes(pg_result($resaco,$iresaco,'j09_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1630,11025,'','".AddSlashes(pg_result($resaco,$iresaco,'j09_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptutaxamatric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j09_iptutaxamatric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j09_iptutaxamatric = $j09_iptutaxamatric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "iptutaxamatric nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j09_iptutaxamatric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "iptutaxamatric nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j09_iptutaxamatric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j09_iptutaxamatric;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptutaxamatric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j09_iptutaxamatric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptutaxamatric ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptutaxamatric.j09_matric";
     $sql .= "      inner join iptutaxamatricexe  on  iptutaxamatricexe.j10_iptutaxamatricexe = iptutaxamatric.j09_iptucadtaxaexe";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join iptutaxamatric  as a on   a.j09_iptutaxamatric = iptutaxamatricexe.j10_iptutaxamatric";
     $sql2 = "";
     if($dbwhere==""){
       if($j09_iptutaxamatric!=null ){
         $sql2 .= " where iptutaxamatric.j09_iptutaxamatric = $j09_iptutaxamatric "; 
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
   function sql_query_file ( $j09_iptutaxamatric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptutaxamatric ";
     $sql2 = "";
     if($dbwhere==""){
       if($j09_iptutaxamatric!=null ){
         $sql2 .= " where iptutaxamatric.j09_iptutaxamatric = $j09_iptutaxamatric "; 
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