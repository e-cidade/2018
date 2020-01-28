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
//CLASSE DA ENTIDADE zonasvalor
class cl_zonasvalor { 
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
   var $j51_zona = 0; 
   var $j51_anousu = 0; 
   var $j51_valorm2t = 0; 
   var $j51_valorm2c = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j51_zona = int8 = Zona Fiscal 
                 j51_anousu = int8 = Exercício 
                 j51_valorm2t = float8 = Valor Terreno (m²) 
                 j51_valorm2c = float8 = Valor Construção (m²) 
                 ";
   //funcao construtor da classe 
   function cl_zonasvalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("zonasvalor"); 
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
       $this->j51_zona = ($this->j51_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["j51_zona"]:$this->j51_zona);
       $this->j51_anousu = ($this->j51_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j51_anousu"]:$this->j51_anousu);
       $this->j51_valorm2t = ($this->j51_valorm2t == ""?@$GLOBALS["HTTP_POST_VARS"]["j51_valorm2t"]:$this->j51_valorm2t);
       $this->j51_valorm2c = ($this->j51_valorm2c == ""?@$GLOBALS["HTTP_POST_VARS"]["j51_valorm2c"]:$this->j51_valorm2c);
     }else{
       $this->j51_zona = ($this->j51_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["j51_zona"]:$this->j51_zona);
       $this->j51_anousu = ($this->j51_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j51_anousu"]:$this->j51_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($j51_zona,$j51_anousu){ 
      $this->atualizacampos();
     if($this->j51_valorm2t == null ){ 
       $this->erro_sql = " Campo Valor Terreno (m²) nao Informado.";
       $this->erro_campo = "j51_valorm2t";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j51_valorm2c == null ){ 
       $this->erro_sql = " Campo Valor Construção (m²) nao Informado.";
       $this->erro_campo = "j51_valorm2c";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j51_zona = $j51_zona; 
       $this->j51_anousu = $j51_anousu; 
     if(($this->j51_zona == null) || ($this->j51_zona == "") ){ 
       $this->erro_sql = " Campo j51_zona nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j51_anousu == null) || ($this->j51_anousu == "") ){ 
       $this->erro_sql = " Campo j51_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into zonasvalor(
                                       j51_zona 
                                      ,j51_anousu 
                                      ,j51_valorm2t 
                                      ,j51_valorm2c 
                       )
                values (
                                $this->j51_zona 
                               ,$this->j51_anousu 
                               ,$this->j51_valorm2t 
                               ,$this->j51_valorm2c 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores por zona fiscal ($this->j51_zona."-".$this->j51_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores por zona fiscal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores por zona fiscal ($this->j51_zona."-".$this->j51_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j51_zona."-".$this->j51_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j51_zona,$this->j51_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5688,'$this->j51_zona','I')");
       $resac = db_query("insert into db_acountkey values($acount,5689,'$this->j51_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,897,5688,'','".AddSlashes(pg_result($resaco,0,'j51_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,897,5689,'','".AddSlashes(pg_result($resaco,0,'j51_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,897,5687,'','".AddSlashes(pg_result($resaco,0,'j51_valorm2t'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,897,6018,'','".AddSlashes(pg_result($resaco,0,'j51_valorm2c'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j51_zona=null,$j51_anousu=null) { 
      $this->atualizacampos();
     $sql = " update zonasvalor set ";
     $virgula = "";
     if(trim($this->j51_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j51_zona"])){ 
       $sql  .= $virgula." j51_zona = $this->j51_zona ";
       $virgula = ",";
       if(trim($this->j51_zona) == null ){ 
         $this->erro_sql = " Campo Zona Fiscal nao Informado.";
         $this->erro_campo = "j51_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j51_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j51_anousu"])){ 
       $sql  .= $virgula." j51_anousu = $this->j51_anousu ";
       $virgula = ",";
       if(trim($this->j51_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "j51_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j51_valorm2t)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j51_valorm2t"])){ 
       $sql  .= $virgula." j51_valorm2t = $this->j51_valorm2t ";
       $virgula = ",";
       if(trim($this->j51_valorm2t) == null ){ 
         $this->erro_sql = " Campo Valor Terreno (m²) nao Informado.";
         $this->erro_campo = "j51_valorm2t";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j51_valorm2c)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j51_valorm2c"])){ 
       $sql  .= $virgula." j51_valorm2c = $this->j51_valorm2c ";
       $virgula = ",";
       if(trim($this->j51_valorm2c) == null ){ 
         $this->erro_sql = " Campo Valor Construção (m²) nao Informado.";
         $this->erro_campo = "j51_valorm2c";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j51_zona!=null){
       $sql .= " j51_zona = $this->j51_zona";
     }
     if($j51_anousu!=null){
       $sql .= " and  j51_anousu = $this->j51_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j51_zona,$this->j51_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5688,'$this->j51_zona','A')");
         $resac = db_query("insert into db_acountkey values($acount,5689,'$this->j51_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j51_zona"]))
           $resac = db_query("insert into db_acount values($acount,897,5688,'".AddSlashes(pg_result($resaco,$conresaco,'j51_zona'))."','$this->j51_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j51_anousu"]))
           $resac = db_query("insert into db_acount values($acount,897,5689,'".AddSlashes(pg_result($resaco,$conresaco,'j51_anousu'))."','$this->j51_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j51_valorm2t"]))
           $resac = db_query("insert into db_acount values($acount,897,5687,'".AddSlashes(pg_result($resaco,$conresaco,'j51_valorm2t'))."','$this->j51_valorm2t',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j51_valorm2c"]))
           $resac = db_query("insert into db_acount values($acount,897,6018,'".AddSlashes(pg_result($resaco,$conresaco,'j51_valorm2c'))."','$this->j51_valorm2c',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores por zona fiscal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j51_zona."-".$this->j51_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores por zona fiscal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j51_zona."-".$this->j51_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j51_zona."-".$this->j51_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j51_zona=null,$j51_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j51_zona,$j51_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5688,'$j51_zona','E')");
         $resac = db_query("insert into db_acountkey values($acount,5689,'$j51_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,897,5688,'','".AddSlashes(pg_result($resaco,$iresaco,'j51_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,897,5689,'','".AddSlashes(pg_result($resaco,$iresaco,'j51_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,897,5687,'','".AddSlashes(pg_result($resaco,$iresaco,'j51_valorm2t'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,897,6018,'','".AddSlashes(pg_result($resaco,$iresaco,'j51_valorm2c'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from zonasvalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j51_zona != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j51_zona = $j51_zona ";
        }
        if($j51_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j51_anousu = $j51_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores por zona fiscal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j51_zona."-".$j51_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores por zona fiscal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j51_zona."-".$j51_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j51_zona."-".$j51_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:zonasvalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j51_zona=null,$j51_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from zonasvalor ";
     $sql .= "      inner join zonas  on  zonas.j50_zona = zonasvalor.j51_zona";
     $sql2 = "";
     if($dbwhere==""){
       if($j51_zona!=null ){
         $sql2 .= " where zonasvalor.j51_zona = $j51_zona "; 
       } 
       if($j51_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " zonasvalor.j51_anousu = $j51_anousu "; 
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
   function sql_query_file ( $j51_zona=null,$j51_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from zonasvalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($j51_zona!=null ){
         $sql2 .= " where zonasvalor.j51_zona = $j51_zona "; 
       } 
       if($j51_anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " zonasvalor.j51_anousu = $j51_anousu "; 
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