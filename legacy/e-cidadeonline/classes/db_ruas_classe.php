<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE ruas
class cl_ruas { 
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
   var $j14_codigo = 0; 
   var $j14_nome = null; 
   var $j14_tipo = null; 
   var $j14_rural = 'f'; 
   var $j14_lei = null; 
   var $j14_dtlei_dia = null; 
   var $j14_dtlei_mes = null; 
   var $j14_dtlei_ano = null; 
   var $j14_dtlei = null; 
   var $j14_bairro = null;
   var $j14_obs   = null;                                  
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j14_codigo = int4 = cód. Logradouro 
                 j14_nome = char(40) = Logradouro 
                 j14_tipo = char(1) = Tipo da Rua 
                 j14_rural = bool = Rural 
                 j14_lei = varchar(20) = Lei 
                 j14_dtlei = date = Data Lei 
                 j14_bairro = varchar(30) = Bairro        
                 j14_obs = text = Observação
                 ";
   //funcao construtor da classe 
   function cl_ruas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ruas"); 
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
       $this->j14_codigo = ($this->j14_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_codigo"]:$this->j14_codigo);
       $this->j14_nome = ($this->j14_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_nome"]:$this->j14_nome);
       $this->j14_tipo = ($this->j14_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_tipo"]:$this->j14_tipo);
       $this->j14_rural = ($this->j14_rural == "f"?@$GLOBALS["HTTP_POST_VARS"]["j14_rural"]:$this->j14_rural);
       $this->j14_lei = ($this->j14_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_lei"]:$this->j14_lei);
       if($this->j14_dtlei == ""){
         $this->j14_dtlei_dia = ($this->j14_dtlei_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_dtlei_dia"]:$this->j14_dtlei_dia);
         $this->j14_dtlei_mes = ($this->j14_dtlei_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_dtlei_mes"]:$this->j14_dtlei_mes);
         $this->j14_dtlei_ano = ($this->j14_dtlei_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_dtlei_ano"]:$this->j14_dtlei_ano);
         if($this->j14_dtlei_dia != ""){
            $this->j14_dtlei = $this->j14_dtlei_ano."-".$this->j14_dtlei_mes."-".$this->j14_dtlei_dia;
         }
       }
       $this->j14_bairro = ($this->j14_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_bairro"]:$this->j14_bairro);
       $this->j14_obs = ($this->j14_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_obs"]:$this->j14_obs);
     }else{
       $this->j14_codigo = ($this->j14_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j14_codigo"]:$this->j14_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j14_codigo){ 
      $this->atualizacampos();
     if($this->j14_nome == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "j14_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j14_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da Rua nao Informado.";
       $this->erro_campo = "j14_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j14_rural == null ){ 
       $this->erro_sql = " Campo Rural nao Informado.";
       $this->erro_campo = "j14_rural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
      if($this->j14_obs == null ){
        $this->erro_sql = " Campo Observação nao Informado.";
        $this->erro_campo = "j14_obs";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg  .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     if($this->j14_dtlei == null ){ 
       $this->j14_dtlei = "null";
     }
       $this->j14_codigo = $j14_codigo; 
     if(($this->j14_codigo == null) || ($this->j14_codigo == "") ){ 
       $this->erro_sql = " Campo j14_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ruas(
                                       j14_codigo 
                                      ,j14_nome 
                                      ,j14_tipo 
                                      ,j14_rural 
                                      ,j14_lei 
                                      ,j14_dtlei 
                                      ,j14_bairro 
                                      ,j14_obs
                       )
                values (
                                $this->j14_codigo 
                               ,'$this->j14_nome' 
                               ,'$this->j14_tipo' 
                               ,'$this->j14_rural' 
                               ,'$this->j14_lei' 
                               ,".($this->j14_dtlei == "null" || $this->j14_dtlei == ""?"null":"'".$this->j14_dtlei."'")." 
                               ,'$this->j14_bairro' 
                               ,'$this->j14_obs'
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ruas/Avenida ($this->j14_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ruas/Avenida já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ruas/Avenida ($this->j14_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j14_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j14_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,53,'$this->j14_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,12,53,'','".AddSlashes(pg_result($resaco,0,'j14_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,12,54,'','".AddSlashes(pg_result($resaco,0,'j14_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,12,55,'','".AddSlashes(pg_result($resaco,0,'j14_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,12,4859,'','".AddSlashes(pg_result($resaco,0,'j14_rural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,12,7374,'','".AddSlashes(pg_result($resaco,0,'j14_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,12,7375,'','".AddSlashes(pg_result($resaco,0,'j14_dtlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,12,7376,'','".AddSlashes(pg_result($resaco,0,'j14_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,12,1009244,'','".AddSlashes(pg_result($resaco,0,'j14_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");

}
     return true;
   } 
   // funcao para alteracao
   function alterar ($j14_codigo=null) { 
      $this->atualizacampos();
     $sql = " update ruas set ";
     $virgula = "";
     if(trim($this->j14_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_codigo"])){ 
       $sql  .= $virgula." j14_codigo = $this->j14_codigo ";
       $virgula = ",";
       if(trim($this->j14_codigo) == null ){ 
         $this->erro_sql = " Campo cód. Logradouro nao Informado.";
         $this->erro_campo = "j14_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j14_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_nome"])){ 
       $sql  .= $virgula." j14_nome = '$this->j14_nome' ";
       $virgula = ",";
       if(trim($this->j14_nome) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "j14_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j14_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_tipo"])){ 
       $sql  .= $virgula." j14_tipo = '$this->j14_tipo' ";
       $virgula = ",";
       if(trim($this->j14_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da Rua nao Informado.";
         $this->erro_campo = "j14_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j14_rural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_rural"])){ 
       $sql  .= $virgula." j14_rural = '$this->j14_rural' ";
       $virgula = ",";
       if(trim($this->j14_rural) == null ){ 
         $this->erro_sql = " Campo Rural nao Informado.";
         $this->erro_campo = "j14_rural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j14_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_lei"])){ 
       $sql  .= $virgula." j14_lei = '$this->j14_lei' ";
       $virgula = ",";
     }
     if(trim($this->j14_dtlei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_dtlei_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j14_dtlei_dia"] !="") ){ 
       $sql  .= $virgula." j14_dtlei = '$this->j14_dtlei' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j14_dtlei_dia"])){ 
         $sql  .= $virgula." j14_dtlei = null ";
         $virgula = ",";
       }
     }
     if(trim($this->j14_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_bairro"])){ 
       $sql  .= $virgula." j14_bairro = '$this->j14_bairro' ";
       $virgula = ",";
     }
     if(trim($this->j14_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j14_obs"])){
       $sql  .= $virgula." j14_obs = '$this->j14_obs' ";
       $virgula = ",";
    }
     $sql .= " where ";
     if($j14_codigo!=null){
       $sql .= " j14_codigo = $this->j14_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j14_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,53,'$this->j14_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_codigo"]))
           $resac = db_query("insert into db_acount values($acount,12,53,'".AddSlashes(pg_result($resaco,$conresaco,'j14_codigo'))."','$this->j14_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_nome"]))
           $resac = db_query("insert into db_acount values($acount,12,54,'".AddSlashes(pg_result($resaco,$conresaco,'j14_nome'))."','$this->j14_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_tipo"]))
           $resac = db_query("insert into db_acount values($acount,12,55,'".AddSlashes(pg_result($resaco,$conresaco,'j14_tipo'))."','$this->j14_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_rural"]))
           $resac = db_query("insert into db_acount values($acount,12,4859,'".AddSlashes(pg_result($resaco,$conresaco,'j14_rural'))."','$this->j14_rural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_lei"]))
           $resac = db_query("insert into db_acount values($acount,12,7374,'".AddSlashes(pg_result($resaco,$conresaco,'j14_lei'))."','$this->j14_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_dtlei"]))
           $resac = db_query("insert into db_acount values($acount,12,7375,'".AddSlashes(pg_result($resaco,$conresaco,'j14_dtlei'))."','$this->j14_dtlei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_bairro"]))
           $resac = db_query("insert into db_acount values($acount,12,7376,'".AddSlashes(pg_result($resaco,$conresaco,'j14_bairro'))."','$this->j14_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j14_obs"]))
           $resac = db_query("insert into db_acount values($acount,12,1009244,'".AddSlashes(pg_result($resaco,$conresaco,'j14_obs'))."','$this->j14_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ruas/Avenida nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j14_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ruas/Avenida nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j14_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j14_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j14_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j14_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,53,'$j14_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,12,53,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,12,54,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,12,55,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,12,4859,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_rural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,12,7374,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,12,7375,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_dtlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,12,7376,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,12,1009244,'','".AddSlashes(pg_result($resaco,$iresaco,'j14_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
      }
     }
     $sql = " delete from ruas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j14_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j14_codigo = $j14_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ruas/Avenida nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j14_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ruas/Avenida nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j14_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j14_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:ruas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j14_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ruas ";
     $sql .= " left join ruastipo on j88_codigo = j14_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($j14_codigo!=null ){
         $sql2 .= " where ruas.j14_codigo = $j14_codigo "; 
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
   function sql_query_ruastipo ( $j14_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ruas ";
     $sql .= " left join cadastro.ruastipo on j14_tipo = j88_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($j14_codigo!=null ){
         $sql2 .= " where ruas.j14_codigo = $j14_codigo "; 
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
   function sql_query_file ( $j14_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ruas ";
     $sql2 = "";
     if($dbwhere==""){
       if($j14_codigo!=null ){
         $sql2 .= " where ruas.j14_codigo = $j14_codigo "; 
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
   function sql_query_lograd ( $j14_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ruas ";
     $sql .= " left join ruascep on j14_codigo = j29_codigo ";
     $sql .= " left join logradcep on j14_codigo = j65_lograd ";
     $sql .= " left join ceplogradouros on j65_ceplog = cp06_codlogradouro";
     $sql2 = "";
     if($dbwhere==""){
       if($j14_codigo!=null ){
         $sql2 .= " where ruas.j14_codigo = $j14_codigo ";
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
  
  /**
   * Método buscarRuasBairro
   * 
   * Executa um left-join com as tabelas ruas bairro e bairro
   * @param integer $j14_codigo
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  public function buscaRuasBairro($j14_codigo = null, $campos = "*", $ordem = null, $dbwhere="") {
    
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
     $sql .= " from ruas ";
     $sql .= " left join ruastipo on j88_codigo = j14_tipo";
     $sql .= "      left join ruasbairro on ruas.j14_codigo = ruasbairro.j16_lograd ";
     $sql .= "      left join bairro     on bairro.j13_codi = ruasbairro.j16_bairro ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($j14_codigo!=null ){
         $sql2 .= " where ruas.j14_codigo = $j14_codigo ";
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