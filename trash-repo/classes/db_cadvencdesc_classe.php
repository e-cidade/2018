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

//MODULO: issqn
//CLASSE DA ENTIDADE cadvencdesc
class cl_cadvencdesc { 
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
   var $q92_codigo = 0; 
   var $q92_descr = null; 
   var $q92_tipo = 0; 
   var $q92_hist = 0; 
   var $q92_diasvcto = 0; 
   var $q92_vlrminimo = 0; 
   var $q92_formacalcparcvenc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q92_codigo = int4 = codigo do vencimento 
                 q92_descr = varchar(40) = descricao do vencimento 
                 q92_tipo = int4 = tipo de debito 
                 q92_hist = int4 = historico de calculo 
                 q92_diasvcto = int4 = Dias para o vencimento 
                 q92_vlrminimo = float8 = Valor Fixado 
                 q92_formacalcparcvenc = int4 = Forma de calculo parc. Vencidas 
                 ";
   //funcao construtor da classe 
   function cl_cadvencdesc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadvencdesc"); 
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
       $this->q92_codigo = ($this->q92_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q92_codigo"]:$this->q92_codigo);
       $this->q92_descr = ($this->q92_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["q92_descr"]:$this->q92_descr);
       $this->q92_tipo = ($this->q92_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q92_tipo"]:$this->q92_tipo);
       $this->q92_hist = ($this->q92_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["q92_hist"]:$this->q92_hist);
       $this->q92_diasvcto = ($this->q92_diasvcto == ""?@$GLOBALS["HTTP_POST_VARS"]["q92_diasvcto"]:$this->q92_diasvcto);
       $this->q92_vlrminimo = ($this->q92_vlrminimo == ""?@$GLOBALS["HTTP_POST_VARS"]["q92_vlrminimo"]:$this->q92_vlrminimo);
       $this->q92_formacalcparcvenc = ($this->q92_formacalcparcvenc == ""?@$GLOBALS["HTTP_POST_VARS"]["q92_formacalcparcvenc"]:$this->q92_formacalcparcvenc);
     }else{
       $this->q92_codigo = ($this->q92_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q92_codigo"]:$this->q92_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($q92_codigo){ 
      $this->atualizacampos();
     if($this->q92_descr == null ){ 
       $this->erro_sql = " Campo descricao do vencimento nao Informado.";
       $this->erro_campo = "q92_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q92_tipo == null ){ 
       $this->erro_sql = " Campo tipo de debito nao Informado.";
       $this->erro_campo = "q92_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q92_hist == null ){ 
       $this->erro_sql = " Campo historico de calculo nao Informado.";
       $this->erro_campo = "q92_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q92_diasvcto == null ){ 
       $this->q92_diasvcto = "0";
     }
     if($this->q92_vlrminimo == null ){ 
       $this->erro_sql = " Campo Valor Fixado nao Informado.";
       $this->erro_campo = "q92_vlrminimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q92_formacalcparcvenc == null ){ 
       $this->erro_sql = " Campo Forma de calculo parc. Vencidas nao Informado.";
       $this->erro_campo = "q92_formacalcparcvenc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q92_codigo == "" || $q92_codigo == null ){
       $result = db_query("select nextval('cadvencdesc_q92_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadvencdesc_q92_codigo_seq do campo: q92_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q92_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadvencdesc_q92_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $q92_codigo)){
         $this->erro_sql = " Campo q92_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q92_codigo = $q92_codigo; 
       }
     }
     if(($this->q92_codigo == null) || ($this->q92_codigo == "") ){ 
       $this->erro_sql = " Campo q92_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadvencdesc(
                                       q92_codigo 
                                      ,q92_descr 
                                      ,q92_tipo 
                                      ,q92_hist 
                                      ,q92_diasvcto 
                                      ,q92_vlrminimo 
                                      ,q92_formacalcparcvenc 
                       )
                values (
                                $this->q92_codigo 
                               ,'$this->q92_descr' 
                               ,$this->q92_tipo 
                               ,$this->q92_hist 
                               ,$this->q92_diasvcto 
                               ,$this->q92_vlrminimo 
                               ,$this->q92_formacalcparcvenc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q92_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q92_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q92_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q92_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,259,'$this->q92_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,54,259,'','".AddSlashes(pg_result($resaco,0,'q92_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,54,260,'','".AddSlashes(pg_result($resaco,0,'q92_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,54,263,'','".AddSlashes(pg_result($resaco,0,'q92_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,54,2400,'','".AddSlashes(pg_result($resaco,0,'q92_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,54,9154,'','".AddSlashes(pg_result($resaco,0,'q92_diasvcto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,54,9816,'','".AddSlashes(pg_result($resaco,0,'q92_vlrminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,54,11133,'','".AddSlashes(pg_result($resaco,0,'q92_formacalcparcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q92_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cadvencdesc set ";
     $virgula = "";
     if(trim($this->q92_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q92_codigo"])){ 
       $sql  .= $virgula." q92_codigo = $this->q92_codigo ";
       $virgula = ",";
       if(trim($this->q92_codigo) == null ){ 
         $this->erro_sql = " Campo codigo do vencimento nao Informado.";
         $this->erro_campo = "q92_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q92_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q92_descr"])){ 
       $sql  .= $virgula." q92_descr = '$this->q92_descr' ";
       $virgula = ",";
       if(trim($this->q92_descr) == null ){ 
         $this->erro_sql = " Campo descricao do vencimento nao Informado.";
         $this->erro_campo = "q92_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q92_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q92_tipo"])){ 
       $sql  .= $virgula." q92_tipo = $this->q92_tipo ";
       $virgula = ",";
       if(trim($this->q92_tipo) == null ){ 
         $this->erro_sql = " Campo tipo de debito nao Informado.";
         $this->erro_campo = "q92_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q92_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q92_hist"])){ 
       $sql  .= $virgula." q92_hist = $this->q92_hist ";
       $virgula = ",";
       if(trim($this->q92_hist) == null ){ 
         $this->erro_sql = " Campo historico de calculo nao Informado.";
         $this->erro_campo = "q92_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q92_diasvcto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q92_diasvcto"])){ 
        if(trim($this->q92_diasvcto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q92_diasvcto"])){ 
           $this->q92_diasvcto = "0" ; 
        } 
       $sql  .= $virgula." q92_diasvcto = $this->q92_diasvcto ";
       $virgula = ",";
     }
     if(trim($this->q92_vlrminimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q92_vlrminimo"])){ 
       $sql  .= $virgula." q92_vlrminimo = $this->q92_vlrminimo ";
       $virgula = ",";
       if(trim($this->q92_vlrminimo) == null ){ 
         $this->erro_sql = " Campo Valor Fixado nao Informado.";
         $this->erro_campo = "q92_vlrminimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q92_formacalcparcvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q92_formacalcparcvenc"])){ 
       $sql  .= $virgula." q92_formacalcparcvenc = $this->q92_formacalcparcvenc ";
       $virgula = ",";
       if(trim($this->q92_formacalcparcvenc) == null ){ 
         $this->erro_sql = " Campo Forma de calculo parc. Vencidas nao Informado.";
         $this->erro_campo = "q92_formacalcparcvenc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q92_codigo!=null){
       $sql .= " q92_codigo = $this->q92_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q92_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,259,'$this->q92_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q92_codigo"]) || $this->q92_codigo != "")
           $resac = db_query("insert into db_acount values($acount,54,259,'".AddSlashes(pg_result($resaco,$conresaco,'q92_codigo'))."','$this->q92_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q92_descr"]) || $this->q92_descr != "")
           $resac = db_query("insert into db_acount values($acount,54,260,'".AddSlashes(pg_result($resaco,$conresaco,'q92_descr'))."','$this->q92_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q92_tipo"]) || $this->q92_tipo != "")
           $resac = db_query("insert into db_acount values($acount,54,263,'".AddSlashes(pg_result($resaco,$conresaco,'q92_tipo'))."','$this->q92_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q92_hist"]) || $this->q92_hist != "")
           $resac = db_query("insert into db_acount values($acount,54,2400,'".AddSlashes(pg_result($resaco,$conresaco,'q92_hist'))."','$this->q92_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q92_diasvcto"]) || $this->q92_diasvcto != "")
           $resac = db_query("insert into db_acount values($acount,54,9154,'".AddSlashes(pg_result($resaco,$conresaco,'q92_diasvcto'))."','$this->q92_diasvcto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q92_vlrminimo"]) || $this->q92_vlrminimo != "")
           $resac = db_query("insert into db_acount values($acount,54,9816,'".AddSlashes(pg_result($resaco,$conresaco,'q92_vlrminimo'))."','$this->q92_vlrminimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q92_formacalcparcvenc"]) || $this->q92_formacalcparcvenc != "")
           $resac = db_query("insert into db_acount values($acount,54,11133,'".AddSlashes(pg_result($resaco,$conresaco,'q92_formacalcparcvenc'))."','$this->q92_formacalcparcvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q92_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q92_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q92_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q92_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q92_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,259,'$q92_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,54,259,'','".AddSlashes(pg_result($resaco,$iresaco,'q92_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,54,260,'','".AddSlashes(pg_result($resaco,$iresaco,'q92_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,54,263,'','".AddSlashes(pg_result($resaco,$iresaco,'q92_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,54,2400,'','".AddSlashes(pg_result($resaco,$iresaco,'q92_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,54,9154,'','".AddSlashes(pg_result($resaco,$iresaco,'q92_diasvcto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,54,9816,'','".AddSlashes(pg_result($resaco,$iresaco,'q92_vlrminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,54,11133,'','".AddSlashes(pg_result($resaco,$iresaco,'q92_formacalcparcvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadvencdesc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q92_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q92_codigo = $q92_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q92_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q92_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q92_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadvencdesc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q92_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadvencdesc ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = cadvencdesc.q92_hist";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = cadvencdesc.q92_tipo";
     $sql .= "      inner join db_config  on  db_config.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($q92_codigo!=null ){
         $sql2 .= " where cadvencdesc.q92_codigo = $q92_codigo "; 
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
   function sql_query_file ( $q92_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadvencdesc ";
     $sql2 = "";
     if($dbwhere==""){
       if($q92_codigo!=null ){
         $sql2 .= " where cadvencdesc.q92_codigo = $q92_codigo "; 
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
   function sql_query_ban ( $q92_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from cadvencdesc ";
     $sql .= "      left join cadvencdescban on cadvencdescban.q93_codigo = cadvencdesc.q92_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($q92_codigo!=null ){
         $sql2 .= " where cadvencdesc.q92_codigo = $q92_codigo ";
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