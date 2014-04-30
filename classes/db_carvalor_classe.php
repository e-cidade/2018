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
//CLASSE DA ENTIDADE carvalor
class cl_carvalor { 
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
   var $j71_codigo = 0; 
   var $j71_anousu = 0; 
   var $j71_caract = 0; 
   var $j71_descr = null; 
   var $j71_valor = 0; 
   var $j71_ini = 0; 
   var $j71_fim = 0; 
   var $j71_quantini = 0; 
   var $j71_quantfim = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j71_codigo = int4 = Código sequencial 
                 j71_anousu = int4 = Ano 
                 j71_caract = int4 = Caracteristica 
                 j71_descr = varchar(40) = Descrição 
                 j71_valor = float8 = Valor da caracteristica 
                 j71_ini = float8 = Valor inicial 
                 j71_fim = float8 = Valor final 
                 j71_quantini = float4 = Quantidade inicial 
                 j71_quantfim = float4 = Quantidade final 
                 ";
   //funcao construtor da classe 
   function cl_carvalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carvalor"); 
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
       $this->j71_codigo = ($this->j71_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_codigo"]:$this->j71_codigo);
       $this->j71_anousu = ($this->j71_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_anousu"]:$this->j71_anousu);
       $this->j71_caract = ($this->j71_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_caract"]:$this->j71_caract);
       $this->j71_descr = ($this->j71_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_descr"]:$this->j71_descr);
       $this->j71_valor = ($this->j71_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_valor"]:$this->j71_valor);
       $this->j71_ini = ($this->j71_ini == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_ini"]:$this->j71_ini);
       $this->j71_fim = ($this->j71_fim == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_fim"]:$this->j71_fim);
       $this->j71_quantini = ($this->j71_quantini == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_quantini"]:$this->j71_quantini);
       $this->j71_quantfim = ($this->j71_quantfim == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_quantfim"]:$this->j71_quantfim);
     }else{
       $this->j71_codigo = ($this->j71_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j71_codigo"]:$this->j71_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j71_codigo){ 
      $this->atualizacampos();
     if($this->j71_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "j71_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j71_caract == null ){ 
       $this->erro_sql = " Campo Caracteristica nao Informado.";
       $this->erro_campo = "j71_caract";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j71_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "j71_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j71_valor == null ){ 
       $this->erro_sql = " Campo Valor da caracteristica nao Informado.";
       $this->erro_campo = "j71_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j71_ini == null ){ 
       $this->erro_sql = " Campo Valor inicial nao Informado.";
       $this->erro_campo = "j71_ini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j71_fim == null ){ 
       $this->erro_sql = " Campo Valor final nao Informado.";
       $this->erro_campo = "j71_fim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j71_quantini == null ){ 
       $this->erro_sql = " Campo Quantidade inicial nao Informado.";
       $this->erro_campo = "j71_quantini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j71_quantfim == null ){ 
       $this->erro_sql = " Campo Quantidade final nao Informado.";
       $this->erro_campo = "j71_quantfim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j71_codigo == "" || $j71_codigo == null ){
       $result = db_query("select nextval('carvalor_j71_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: carvalor_j71_codigo_seq do campo: j71_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j71_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from carvalor_j71_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j71_codigo)){
         $this->erro_sql = " Campo j71_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j71_codigo = $j71_codigo; 
       }
     }
     if(($this->j71_codigo == null) || ($this->j71_codigo == "") ){ 
       $this->erro_sql = " Campo j71_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carvalor(
                                       j71_codigo 
                                      ,j71_anousu 
                                      ,j71_caract 
                                      ,j71_descr 
                                      ,j71_valor 
                                      ,j71_ini 
                                      ,j71_fim 
                                      ,j71_quantini 
                                      ,j71_quantfim 
                       )
                values (
                                $this->j71_codigo 
                               ,$this->j71_anousu 
                               ,$this->j71_caract 
                               ,'$this->j71_descr' 
                               ,$this->j71_valor 
                               ,$this->j71_ini 
                               ,$this->j71_fim 
                               ,$this->j71_quantini 
                               ,$this->j71_quantfim 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores das caracteristicas ($this->j71_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores das caracteristicas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores das caracteristicas ($this->j71_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j71_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j71_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9761,'$this->j71_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1140,9761,'','".AddSlashes(pg_result($resaco,0,'j71_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1140,6925,'','".AddSlashes(pg_result($resaco,0,'j71_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1140,6926,'','".AddSlashes(pg_result($resaco,0,'j71_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1140,7583,'','".AddSlashes(pg_result($resaco,0,'j71_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1140,6927,'','".AddSlashes(pg_result($resaco,0,'j71_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1140,7584,'','".AddSlashes(pg_result($resaco,0,'j71_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1140,7585,'','".AddSlashes(pg_result($resaco,0,'j71_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1140,10993,'','".AddSlashes(pg_result($resaco,0,'j71_quantini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1140,10994,'','".AddSlashes(pg_result($resaco,0,'j71_quantfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j71_codigo=null) { 
      $this->atualizacampos();
     $sql = " update carvalor set ";
     $virgula = "";
     if(trim($this->j71_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_codigo"])){ 
       $sql  .= $virgula." j71_codigo = $this->j71_codigo ";
       $virgula = ",";
       if(trim($this->j71_codigo) == null ){ 
         $this->erro_sql = " Campo Código sequencial nao Informado.";
         $this->erro_campo = "j71_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j71_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_anousu"])){ 
       $sql  .= $virgula." j71_anousu = $this->j71_anousu ";
       $virgula = ",";
       if(trim($this->j71_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j71_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j71_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_caract"])){ 
       $sql  .= $virgula." j71_caract = $this->j71_caract ";
       $virgula = ",";
       if(trim($this->j71_caract) == null ){ 
         $this->erro_sql = " Campo Caracteristica nao Informado.";
         $this->erro_campo = "j71_caract";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j71_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_descr"])){ 
       $sql  .= $virgula." j71_descr = '$this->j71_descr' ";
       $virgula = ",";
       if(trim($this->j71_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "j71_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j71_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_valor"])){ 
       $sql  .= $virgula." j71_valor = $this->j71_valor ";
       $virgula = ",";
       if(trim($this->j71_valor) == null ){ 
         $this->erro_sql = " Campo Valor da caracteristica nao Informado.";
         $this->erro_campo = "j71_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j71_ini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_ini"])){ 
       $sql  .= $virgula." j71_ini = $this->j71_ini ";
       $virgula = ",";
       if(trim($this->j71_ini) == null ){ 
         $this->erro_sql = " Campo Valor inicial nao Informado.";
         $this->erro_campo = "j71_ini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j71_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_fim"])){ 
       $sql  .= $virgula." j71_fim = $this->j71_fim ";
       $virgula = ",";
       if(trim($this->j71_fim) == null ){ 
         $this->erro_sql = " Campo Valor final nao Informado.";
         $this->erro_campo = "j71_fim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j71_quantini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_quantini"])){ 
       $sql  .= $virgula." j71_quantini = $this->j71_quantini ";
       $virgula = ",";
       if(trim($this->j71_quantini) == null ){ 
         $this->erro_sql = " Campo Quantidade inicial nao Informado.";
         $this->erro_campo = "j71_quantini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j71_quantfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j71_quantfim"])){ 
       $sql  .= $virgula." j71_quantfim = $this->j71_quantfim ";
       $virgula = ",";
       if(trim($this->j71_quantfim) == null ){ 
         $this->erro_sql = " Campo Quantidade final nao Informado.";
         $this->erro_campo = "j71_quantfim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j71_codigo!=null){
       $sql .= " j71_codigo = $this->j71_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j71_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9761,'$this->j71_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_codigo"]) || $this->j71_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1140,9761,'".AddSlashes(pg_result($resaco,$conresaco,'j71_codigo'))."','$this->j71_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_anousu"]) || $this->j71_anousu != "")
           $resac = db_query("insert into db_acount values($acount,1140,6925,'".AddSlashes(pg_result($resaco,$conresaco,'j71_anousu'))."','$this->j71_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_caract"]) || $this->j71_caract != "")
           $resac = db_query("insert into db_acount values($acount,1140,6926,'".AddSlashes(pg_result($resaco,$conresaco,'j71_caract'))."','$this->j71_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_descr"]) || $this->j71_descr != "")
           $resac = db_query("insert into db_acount values($acount,1140,7583,'".AddSlashes(pg_result($resaco,$conresaco,'j71_descr'))."','$this->j71_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_valor"]) || $this->j71_valor != "")
           $resac = db_query("insert into db_acount values($acount,1140,6927,'".AddSlashes(pg_result($resaco,$conresaco,'j71_valor'))."','$this->j71_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_ini"]) || $this->j71_ini != "")
           $resac = db_query("insert into db_acount values($acount,1140,7584,'".AddSlashes(pg_result($resaco,$conresaco,'j71_ini'))."','$this->j71_ini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_fim"]) || $this->j71_fim != "")
           $resac = db_query("insert into db_acount values($acount,1140,7585,'".AddSlashes(pg_result($resaco,$conresaco,'j71_fim'))."','$this->j71_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_quantini"]) || $this->j71_quantini != "")
           $resac = db_query("insert into db_acount values($acount,1140,10993,'".AddSlashes(pg_result($resaco,$conresaco,'j71_quantini'))."','$this->j71_quantini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j71_quantfim"]) || $this->j71_quantfim != "")
           $resac = db_query("insert into db_acount values($acount,1140,10994,'".AddSlashes(pg_result($resaco,$conresaco,'j71_quantfim'))."','$this->j71_quantfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores das caracteristicas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j71_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores das caracteristicas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j71_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j71_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j71_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j71_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9761,'$j71_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1140,9761,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1140,6925,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1140,6926,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1140,7583,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1140,6927,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1140,7584,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_ini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1140,7585,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1140,10993,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_quantini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1140,10994,'','".AddSlashes(pg_result($resaco,$iresaco,'j71_quantfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carvalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j71_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j71_codigo = $j71_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores das caracteristicas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j71_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores das caracteristicas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j71_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j71_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:carvalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j71_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carvalor ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = carvalor.j71_caract";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j71_codigo!=null ){
         $sql2 .= " where carvalor.j71_codigo = $j71_codigo "; 
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
   function sql_query_file ( $j71_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carvalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($j71_codigo!=null ){
         $sql2 .= " where carvalor.j71_codigo = $j71_codigo "; 
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