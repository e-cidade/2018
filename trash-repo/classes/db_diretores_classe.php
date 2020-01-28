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

//MODULO: educação
//CLASSE DA ENTIDADE diretores
class cl_diretores { 
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
   var $ed15_i_codigo = 0; 
   var $ed15_i_escolas = 0; 
   var $ed15_c_categoria = null; 
   var $ed15_d_inicio_dia = null; 
   var $ed15_d_inicio_mes = null; 
   var $ed15_d_inicio_ano = null; 
   var $ed15_d_inicio = null; 
   var $ed15_d_termino_dia = null; 
   var $ed15_d_termino_mes = null; 
   var $ed15_d_termino_ano = null; 
   var $ed15_d_termino = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed15_i_codigo = int8 = CGM do Diretor 
                 ed15_i_escolas = int8 = CGM da Escola 
                 ed15_c_categoria = char(30) = Categoria 
                 ed15_d_inicio = date = Início 
                 ed15_d_termino = date = Término 
                 ";
   //funcao construtor da classe 
   function cl_diretores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diretores"); 
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
       $this->ed15_i_codigo = ($this->ed15_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_i_codigo"]:$this->ed15_i_codigo);
       $this->ed15_i_escolas = ($this->ed15_i_escolas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_i_escolas"]:$this->ed15_i_escolas);
       $this->ed15_c_categoria = ($this->ed15_c_categoria == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_c_categoria"]:$this->ed15_c_categoria);
       if($this->ed15_d_inicio == ""){
         $this->ed15_d_inicio_dia = ($this->ed15_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_d_inicio_dia"]:$this->ed15_d_inicio_dia);
         $this->ed15_d_inicio_mes = ($this->ed15_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_d_inicio_mes"]:$this->ed15_d_inicio_mes);
         $this->ed15_d_inicio_ano = ($this->ed15_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_d_inicio_ano"]:$this->ed15_d_inicio_ano);
         if($this->ed15_d_inicio_dia != ""){
            $this->ed15_d_inicio = $this->ed15_d_inicio_ano."-".$this->ed15_d_inicio_mes."-".$this->ed15_d_inicio_dia;
         }
       }
       if($this->ed15_d_termino == ""){
         $this->ed15_d_termino_dia = ($this->ed15_d_termino_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_d_termino_dia"]:$this->ed15_d_termino_dia);
         $this->ed15_d_termino_mes = ($this->ed15_d_termino_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_d_termino_mes"]:$this->ed15_d_termino_mes);
         $this->ed15_d_termino_ano = ($this->ed15_d_termino_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_d_termino_ano"]:$this->ed15_d_termino_ano);
         if($this->ed15_d_termino_dia != ""){
            $this->ed15_d_termino = $this->ed15_d_termino_ano."-".$this->ed15_d_termino_mes."-".$this->ed15_d_termino_dia;
         }
       }
     }else{
       $this->ed15_i_codigo = ($this->ed15_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed15_i_codigo"]:$this->ed15_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed15_i_codigo){ 
      $this->atualizacampos();
     if($this->ed15_i_escolas == null ){ 
       $this->erro_sql = " Campo CGM da Escola nao Informado.";
       $this->erro_campo = "ed15_i_escolas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed15_c_categoria == null ){ 
       $this->erro_sql = " Campo Categoria nao Informado.";
       $this->erro_campo = "ed15_c_categoria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed15_d_inicio == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "ed15_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed15_d_termino == null ){ 
       $this->erro_sql = " Campo Término nao Informado.";
       $this->erro_campo = "ed15_d_termino_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->ed15_i_codigo = $ed15_i_codigo; 
     if(($this->ed15_i_codigo == null) || ($this->ed15_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed15_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diretores(
                                       ed15_i_codigo 
                                      ,ed15_i_escolas 
                                      ,ed15_c_categoria 
                                      ,ed15_d_inicio 
                                      ,ed15_d_termino 
                       )
                values (
                                $this->ed15_i_codigo 
                               ,$this->ed15_i_escolas 
                               ,'$this->ed15_c_categoria' 
                               ,".($this->ed15_d_inicio == "null" || $this->ed15_d_inicio == ""?"null":"'".$this->ed15_d_inicio."'")." 
                               ,".($this->ed15_d_termino == "null" || $this->ed15_d_termino == ""?"null":"'".$this->ed15_d_termino."'")." 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diretores ($this->ed15_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diretores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diretores ($this->ed15_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed15_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed15_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006018,'$this->ed15_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006002,1006018,'','".AddSlashes(pg_result($resaco,0,'ed15_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006002,1006058,'','".AddSlashes(pg_result($resaco,0,'ed15_i_escolas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006002,1006019,'','".AddSlashes(pg_result($resaco,0,'ed15_c_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006002,1006020,'','".AddSlashes(pg_result($resaco,0,'ed15_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006002,1006021,'','".AddSlashes(pg_result($resaco,0,'ed15_d_termino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed15_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update diretores set ";
     $virgula = "";
     if(trim($this->ed15_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed15_i_codigo"])){ 
       $sql  .= $virgula." ed15_i_codigo = $this->ed15_i_codigo ";
       $virgula = ",";
       if(trim($this->ed15_i_codigo) == null ){ 
         $this->erro_sql = " Campo CGM do Diretor nao Informado.";
         $this->erro_campo = "ed15_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed15_i_escolas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed15_i_escolas"])){ 
       $sql  .= $virgula." ed15_i_escolas = $this->ed15_i_escolas ";
       $virgula = ",";
       if(trim($this->ed15_i_escolas) == null ){ 
         $this->erro_sql = " Campo CGM da Escola nao Informado.";
         $this->erro_campo = "ed15_i_escolas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed15_c_categoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed15_c_categoria"])){ 
       $sql  .= $virgula." ed15_c_categoria = '$this->ed15_c_categoria' ";
       $virgula = ",";
       if(trim($this->ed15_c_categoria) == null ){ 
         $this->erro_sql = " Campo Categoria nao Informado.";
         $this->erro_campo = "ed15_c_categoria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed15_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed15_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed15_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." ed15_d_inicio = '$this->ed15_d_inicio' ";
       $virgula = ",";
       if(trim($this->ed15_d_inicio) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "ed15_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed15_d_inicio_dia"])){ 
         $sql  .= $virgula." ed15_d_inicio = null ";
         $virgula = ",";
         if(trim($this->ed15_d_inicio) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "ed15_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed15_d_termino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed15_d_termino_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed15_d_termino_dia"] !="") ){ 
       $sql  .= $virgula." ed15_d_termino = '$this->ed15_d_termino' ";
       $virgula = ",";
       if(trim($this->ed15_d_termino) == null ){ 
         $this->erro_sql = " Campo Término nao Informado.";
         $this->erro_campo = "ed15_d_termino_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed15_d_termino_dia"])){ 
         $sql  .= $virgula." ed15_d_termino = null ";
         $virgula = ",";
         if(trim($this->ed15_d_termino) == null ){ 
           $this->erro_sql = " Campo Término nao Informado.";
           $this->erro_campo = "ed15_d_termino_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ed15_i_codigo!=null){
       $sql .= " ed15_i_codigo = $this->ed15_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed15_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006018,'$this->ed15_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed15_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006002,1006018,'".AddSlashes(pg_result($resaco,$conresaco,'ed15_i_codigo'))."','$this->ed15_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed15_i_escolas"]))
           $resac = pg_query("insert into db_acount values($acount,1006002,1006058,'".AddSlashes(pg_result($resaco,$conresaco,'ed15_i_escolas'))."','$this->ed15_i_escolas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed15_c_categoria"]))
           $resac = pg_query("insert into db_acount values($acount,1006002,1006019,'".AddSlashes(pg_result($resaco,$conresaco,'ed15_c_categoria'))."','$this->ed15_c_categoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed15_d_inicio"]))
           $resac = pg_query("insert into db_acount values($acount,1006002,1006020,'".AddSlashes(pg_result($resaco,$conresaco,'ed15_d_inicio'))."','$this->ed15_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed15_d_termino"]))
           $resac = pg_query("insert into db_acount values($acount,1006002,1006021,'".AddSlashes(pg_result($resaco,$conresaco,'ed15_d_termino'))."','$this->ed15_d_termino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diretores nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed15_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diretores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed15_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed15_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006018,'$ed15_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006002,1006018,'','".AddSlashes(pg_result($resaco,$iresaco,'ed15_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006002,1006058,'','".AddSlashes(pg_result($resaco,$iresaco,'ed15_i_escolas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006002,1006019,'','".AddSlashes(pg_result($resaco,$iresaco,'ed15_c_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006002,1006020,'','".AddSlashes(pg_result($resaco,$iresaco,'ed15_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006002,1006021,'','".AddSlashes(pg_result($resaco,$iresaco,'ed15_d_termino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from diretores
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed15_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed15_i_codigo = $ed15_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diretores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed15_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diretores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed15_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:diretores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed15_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diretores ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = diretores.ed15_i_codigo";
     $sql .= "      inner join escolas  on  escolas.ed02_i_codigo = diretores.ed15_i_escolas";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = escolas.ed02_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed15_i_codigo!=null ){
         $sql2 .= " where diretores.ed15_i_codigo = $ed15_i_codigo "; 
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
   function sql_query_file ( $ed15_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diretores ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed15_i_codigo!=null ){
         $sql2 .= " where diretores.ed15_i_codigo = $ed15_i_codigo "; 
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