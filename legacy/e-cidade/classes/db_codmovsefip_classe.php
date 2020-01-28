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

//MODULO: pessoal
//CLASSE DA ENTIDADE codmovsefip
class cl_codmovsefip { 
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
   var $r66_anousu = 0; 
   var $r66_mesusu = 0; 
   var $r66_codigo = null; 
   var $r66_descr = null; 
   var $r66_tipo = null; 
   var $r66_mensal = 'f'; 
   var $r66_ifgtsc = null; 
   var $r66_ifgtse = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r66_anousu = float4 = Ano do Exerc�cio 
                 r66_mesusu = float4 = M�s do Exerc�cio 
                 r66_codigo = varchar(2) = C�digo da Movimenta��o 
                 r66_descr = varchar(40) = Descri��o 
                 r66_tipo = varchar(1) = Tipo de Afastamento 
                 r66_mensal = bool = Mensal (S/N) 
                 r66_ifgtsc = varchar(1) = Recolhe FGTS P/ CLT 
                 r66_ifgtse = varchar(1) = Recolhe FGTS p/ Estatut�rio 
                 ";
   //funcao construtor da classe 
   function cl_codmovsefip() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("codmovsefip"); 
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
       $this->r66_anousu = ($this->r66_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_anousu"]:$this->r66_anousu);
       $this->r66_mesusu = ($this->r66_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_mesusu"]:$this->r66_mesusu);
       $this->r66_codigo = ($this->r66_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_codigo"]:$this->r66_codigo);
       $this->r66_descr = ($this->r66_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_descr"]:$this->r66_descr);
       $this->r66_tipo = ($this->r66_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_tipo"]:$this->r66_tipo);
       $this->r66_mensal = ($this->r66_mensal == "f"?@$GLOBALS["HTTP_POST_VARS"]["r66_mensal"]:$this->r66_mensal);
       $this->r66_ifgtsc = ($this->r66_ifgtsc == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_ifgtsc"]:$this->r66_ifgtsc);
       $this->r66_ifgtse = ($this->r66_ifgtse == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_ifgtse"]:$this->r66_ifgtse);
     }else{
       $this->r66_anousu = ($this->r66_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_anousu"]:$this->r66_anousu);
       $this->r66_mesusu = ($this->r66_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_mesusu"]:$this->r66_mesusu);
       $this->r66_codigo = ($this->r66_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r66_codigo"]:$this->r66_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r66_anousu,$r66_mesusu,$r66_codigo){ 
      $this->atualizacampos();
     if($this->r66_descr == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "r66_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r66_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Afastamento nao Informado.";
       $this->erro_campo = "r66_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r66_mensal == null ){ 
       $this->erro_sql = " Campo Mensal (S/N) nao Informado.";
       $this->erro_campo = "r66_mensal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r66_ifgtsc == null ){ 
       $this->r66_ifgtsc = "S";
     }
     if($this->r66_ifgtse == null ){ 
       $this->r66_ifgtse = "N";
     }
       $this->r66_anousu = $r66_anousu; 
       $this->r66_mesusu = $r66_mesusu; 
       $this->r66_codigo = $r66_codigo; 
     if(($this->r66_anousu == null) || ($this->r66_anousu == "") ){ 
       $this->erro_sql = " Campo r66_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r66_mesusu == null) || ($this->r66_mesusu == "") ){ 
       $this->erro_sql = " Campo r66_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r66_codigo == null) || ($this->r66_codigo == "") ){ 
       $this->erro_sql = " Campo r66_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into codmovsefip(
                                       r66_anousu 
                                      ,r66_mesusu 
                                      ,r66_codigo 
                                      ,r66_descr 
                                      ,r66_tipo 
                                      ,r66_mensal 
                                      ,r66_ifgtsc 
                                      ,r66_ifgtse 
                       )
                values (
                                $this->r66_anousu 
                               ,$this->r66_mesusu 
                               ,'$this->r66_codigo' 
                               ,'$this->r66_descr' 
                               ,'$this->r66_tipo' 
                               ,'$this->r66_mensal' 
                               ,'$this->r66_ifgtsc' 
                               ,'$this->r66_ifgtse' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimenta��es da Sefip ($this->r66_anousu."-".$this->r66_mesusu."-".$this->r66_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimenta��es da Sefip j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimenta��es da Sefip ($this->r66_anousu."-".$this->r66_mesusu."-".$this->r66_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r66_anousu."-".$this->r66_mesusu."-".$this->r66_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r66_anousu,$this->r66_mesusu,$this->r66_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4566,'$this->r66_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4567,'$this->r66_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4568,'$this->r66_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,605,4566,'','".AddSlashes(pg_result($resaco,0,'r66_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,605,4567,'','".AddSlashes(pg_result($resaco,0,'r66_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,605,4568,'','".AddSlashes(pg_result($resaco,0,'r66_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,605,4569,'','".AddSlashes(pg_result($resaco,0,'r66_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,605,4570,'','".AddSlashes(pg_result($resaco,0,'r66_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,605,4571,'','".AddSlashes(pg_result($resaco,0,'r66_mensal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,605,4572,'','".AddSlashes(pg_result($resaco,0,'r66_ifgtsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,605,4573,'','".AddSlashes(pg_result($resaco,0,'r66_ifgtse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r66_anousu=null,$r66_mesusu=null,$r66_codigo=null) { 
      $this->atualizacampos();
     $sql = " update codmovsefip set ";
     $virgula = "";
     if(trim($this->r66_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r66_anousu"])){ 
       $sql  .= $virgula." r66_anousu = $this->r66_anousu ";
       $virgula = ",";
       if(trim($this->r66_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exerc�cio nao Informado.";
         $this->erro_campo = "r66_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r66_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r66_mesusu"])){ 
       $sql  .= $virgula." r66_mesusu = $this->r66_mesusu ";
       $virgula = ",";
       if(trim($this->r66_mesusu) == null ){ 
         $this->erro_sql = " Campo M�s do Exerc�cio nao Informado.";
         $this->erro_campo = "r66_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r66_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r66_codigo"])){ 
       $sql  .= $virgula." r66_codigo = '$this->r66_codigo' ";
       $virgula = ",";
       if(trim($this->r66_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo da Movimenta��o nao Informado.";
         $this->erro_campo = "r66_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r66_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r66_descr"])){ 
       $sql  .= $virgula." r66_descr = '$this->r66_descr' ";
       $virgula = ",";
       if(trim($this->r66_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "r66_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r66_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r66_tipo"])){ 
       $sql  .= $virgula." r66_tipo = '$this->r66_tipo' ";
       $virgula = ",";
       if(trim($this->r66_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Afastamento nao Informado.";
         $this->erro_campo = "r66_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r66_mensal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r66_mensal"])){ 
       $sql  .= $virgula." r66_mensal = '$this->r66_mensal' ";
       $virgula = ",";
       if(trim($this->r66_mensal) == null ){ 
         $this->erro_sql = " Campo Mensal (S/N) nao Informado.";
         $this->erro_campo = "r66_mensal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r66_ifgtsc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r66_ifgtsc"])){ 
       $sql  .= $virgula." r66_ifgtsc = '$this->r66_ifgtsc' ";
       $virgula = ",";
     }
     if(trim($this->r66_ifgtse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r66_ifgtse"])){ 
       $sql  .= $virgula." r66_ifgtse = '$this->r66_ifgtse' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($r66_anousu!=null){
       $sql .= " r66_anousu = $this->r66_anousu";
     }
     if($r66_mesusu!=null){
       $sql .= " and  r66_mesusu = $this->r66_mesusu";
     }
     if($r66_codigo!=null){
       $sql .= " and  r66_codigo = '$this->r66_codigo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r66_anousu,$this->r66_mesusu,$this->r66_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4566,'$this->r66_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4567,'$this->r66_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4568,'$this->r66_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r66_anousu"]))
           $resac = db_query("insert into db_acount values($acount,605,4566,'".AddSlashes(pg_result($resaco,$conresaco,'r66_anousu'))."','$this->r66_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r66_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,605,4567,'".AddSlashes(pg_result($resaco,$conresaco,'r66_mesusu'))."','$this->r66_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r66_codigo"]))
           $resac = db_query("insert into db_acount values($acount,605,4568,'".AddSlashes(pg_result($resaco,$conresaco,'r66_codigo'))."','$this->r66_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r66_descr"]))
           $resac = db_query("insert into db_acount values($acount,605,4569,'".AddSlashes(pg_result($resaco,$conresaco,'r66_descr'))."','$this->r66_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r66_tipo"]))
           $resac = db_query("insert into db_acount values($acount,605,4570,'".AddSlashes(pg_result($resaco,$conresaco,'r66_tipo'))."','$this->r66_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r66_mensal"]))
           $resac = db_query("insert into db_acount values($acount,605,4571,'".AddSlashes(pg_result($resaco,$conresaco,'r66_mensal'))."','$this->r66_mensal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r66_ifgtsc"]))
           $resac = db_query("insert into db_acount values($acount,605,4572,'".AddSlashes(pg_result($resaco,$conresaco,'r66_ifgtsc'))."','$this->r66_ifgtsc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r66_ifgtse"]))
           $resac = db_query("insert into db_acount values($acount,605,4573,'".AddSlashes(pg_result($resaco,$conresaco,'r66_ifgtse'))."','$this->r66_ifgtse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimenta��es da Sefip nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r66_anousu."-".$this->r66_mesusu."-".$this->r66_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimenta��es da Sefip nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r66_anousu."-".$this->r66_mesusu."-".$this->r66_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r66_anousu."-".$this->r66_mesusu."-".$this->r66_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r66_anousu=null,$r66_mesusu=null,$r66_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r66_anousu,$r66_mesusu,$r66_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4566,'$r66_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4567,'$r66_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4568,'$r66_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,605,4566,'','".AddSlashes(pg_result($resaco,$iresaco,'r66_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,605,4567,'','".AddSlashes(pg_result($resaco,$iresaco,'r66_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,605,4568,'','".AddSlashes(pg_result($resaco,$iresaco,'r66_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,605,4569,'','".AddSlashes(pg_result($resaco,$iresaco,'r66_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,605,4570,'','".AddSlashes(pg_result($resaco,$iresaco,'r66_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,605,4571,'','".AddSlashes(pg_result($resaco,$iresaco,'r66_mensal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,605,4572,'','".AddSlashes(pg_result($resaco,$iresaco,'r66_ifgtsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,605,4573,'','".AddSlashes(pg_result($resaco,$iresaco,'r66_ifgtse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from codmovsefip
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r66_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r66_anousu = $r66_anousu ";
        }
        if($r66_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r66_mesusu = $r66_mesusu ";
        }
        if($r66_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r66_codigo = '$r66_codigo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimenta��es da Sefip nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r66_anousu."-".$r66_mesusu."-".$r66_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Movimenta��es da Sefip nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r66_anousu."-".$r66_mesusu."-".$r66_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r66_anousu."-".$r66_mesusu."-".$r66_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:codmovsefip";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r66_anousu,$this->r66_mesusu,$this->r66_codigo);
   }
   function sql_query ( $r66_anousu=null,$r66_mesusu=null,$r66_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from codmovsefip ";
     $sql2 = "";
     if($dbwhere==""){
       if($r66_anousu!=null ){
         $sql2 .= " where codmovsefip.r66_anousu = $r66_anousu "; 
       } 
       if($r66_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " codmovsefip.r66_mesusu = $r66_mesusu "; 
       } 
       if($r66_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " codmovsefip.r66_codigo = '$r66_codigo' "; 
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
   function sql_query_file ( $r66_anousu=null,$r66_mesusu=null,$r66_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from codmovsefip ";
     $sql2 = "";
     if($dbwhere==""){
       if($r66_anousu!=null ){
         $sql2 .= " where codmovsefip.r66_anousu = $r66_anousu "; 
       } 
       if($r66_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " codmovsefip.r66_mesusu = $r66_mesusu "; 
       } 
       if($r66_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " codmovsefip.r66_codigo = '$r66_codigo' "; 
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