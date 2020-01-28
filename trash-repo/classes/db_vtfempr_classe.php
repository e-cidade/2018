<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE vtfempr
class cl_vtfempr { 
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
   var $r16_instit = 0; 
   var $r16_anousu = 0; 
   var $r16_mesusu = 0; 
   var $r16_codigo = null; 
   var $r16_descr = null; 
   var $r16_valor = 0; 
   var $r16_perc = 0; 
   var $r16_empres = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r16_instit = int4 = Cod. Instituição 
                 r16_anousu = int4 = Ano 
                 r16_mesusu = int4 = Mês 
                 r16_codigo = char(4) = Código 
                 r16_descr = char(30) = Descrição 
                 r16_valor = float8 = Valor 
                 r16_perc = float8 = Percentual 
                 r16_empres = int4 = Empresa 
                 ";
   //funcao construtor da classe 
   function cl_vtfempr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vtfempr"); 
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
       $this->r16_instit = ($this->r16_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_instit"]:$this->r16_instit);
       $this->r16_anousu = ($this->r16_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_anousu"]:$this->r16_anousu);
       $this->r16_mesusu = ($this->r16_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_mesusu"]:$this->r16_mesusu);
       $this->r16_codigo = ($this->r16_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_codigo"]:$this->r16_codigo);
       $this->r16_descr = ($this->r16_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_descr"]:$this->r16_descr);
       $this->r16_valor = ($this->r16_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_valor"]:$this->r16_valor);
       $this->r16_perc = ($this->r16_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_perc"]:$this->r16_perc);
       $this->r16_empres = ($this->r16_empres == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_empres"]:$this->r16_empres);
     }else{
       $this->r16_instit = ($this->r16_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_instit"]:$this->r16_instit);
       $this->r16_anousu = ($this->r16_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_anousu"]:$this->r16_anousu);
       $this->r16_mesusu = ($this->r16_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_mesusu"]:$this->r16_mesusu);
       $this->r16_codigo = ($this->r16_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r16_codigo"]:$this->r16_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($r16_anousu,$r16_mesusu,$r16_codigo,$r16_instit){ 
      $this->atualizacampos();
     if($this->r16_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r16_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r16_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r16_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r16_perc == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "r16_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r16_empres == null ){ 
       $this->erro_sql = " Campo Empresa nao Informado.";
       $this->erro_campo = "r16_empres";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r16_anousu = $r16_anousu; 
       $this->r16_mesusu = $r16_mesusu; 
       $this->r16_codigo = $r16_codigo; 
       $this->r16_instit = $r16_instit; 
     if(($this->r16_anousu == null) || ($this->r16_anousu == "") ){ 
       $this->erro_sql = " Campo r16_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r16_mesusu == null) || ($this->r16_mesusu == "") ){ 
       $this->erro_sql = " Campo r16_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r16_codigo == null) || ($this->r16_codigo == "") ){ 
       $this->erro_sql = " Campo r16_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r16_instit == null) || ($this->r16_instit == "") ){ 
       $this->erro_sql = " Campo r16_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vtfempr(
                                       r16_instit 
                                      ,r16_anousu 
                                      ,r16_mesusu 
                                      ,r16_codigo 
                                      ,r16_descr 
                                      ,r16_valor 
                                      ,r16_perc 
                                      ,r16_empres 
                       )
                values (
                                $this->r16_instit 
                               ,$this->r16_anousu 
                               ,$this->r16_mesusu 
                               ,'$this->r16_codigo' 
                               ,'$this->r16_descr' 
                               ,$this->r16_valor 
                               ,$this->r16_perc 
                               ,$this->r16_empres 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empresas para Vale Transportes ($this->r16_anousu."-".$this->r16_mesusu."-".$this->r16_codigo."-".$this->r16_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empresas para Vale Transportes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empresas para Vale Transportes ($this->r16_anousu."-".$this->r16_mesusu."-".$this->r16_codigo."-".$this->r16_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r16_anousu."-".$this->r16_mesusu."-".$this->r16_codigo."-".$this->r16_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r16_anousu,$this->r16_mesusu,$this->r16_codigo,$this->r16_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4532,'$this->r16_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4533,'$this->r16_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4534,'$this->r16_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,9912,'$this->r16_instit','I')");
       $resac = db_query("insert into db_acount values($acount,600,9912,'','".AddSlashes(pg_result($resaco,0,'r16_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,600,4532,'','".AddSlashes(pg_result($resaco,0,'r16_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,600,4533,'','".AddSlashes(pg_result($resaco,0,'r16_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,600,4534,'','".AddSlashes(pg_result($resaco,0,'r16_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,600,4535,'','".AddSlashes(pg_result($resaco,0,'r16_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,600,4536,'','".AddSlashes(pg_result($resaco,0,'r16_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,600,4537,'','".AddSlashes(pg_result($resaco,0,'r16_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,600,4538,'','".AddSlashes(pg_result($resaco,0,'r16_empres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r16_anousu=null,$r16_mesusu=null,$r16_codigo=null,$r16_instit=null) { 
      $this->atualizacampos();
     $sql = " update vtfempr set ";
     $virgula = "";
     if(trim($this->r16_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r16_instit"])){ 
       $sql  .= $virgula." r16_instit = $this->r16_instit ";
       $virgula = ",";
       if(trim($this->r16_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r16_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r16_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r16_anousu"])){ 
       $sql  .= $virgula." r16_anousu = $this->r16_anousu ";
       $virgula = ",";
       if(trim($this->r16_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "r16_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r16_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r16_mesusu"])){ 
       $sql  .= $virgula." r16_mesusu = $this->r16_mesusu ";
       $virgula = ",";
       if(trim($this->r16_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "r16_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r16_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r16_codigo"])){ 
       $sql  .= $virgula." r16_codigo = '$this->r16_codigo' ";
       $virgula = ",";
       if(trim($this->r16_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "r16_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r16_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r16_descr"])){ 
       $sql  .= $virgula." r16_descr = '$this->r16_descr' ";
       $virgula = ",";
       if(trim($this->r16_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r16_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r16_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r16_valor"])){ 
       $sql  .= $virgula." r16_valor = $this->r16_valor ";
       $virgula = ",";
       if(trim($this->r16_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r16_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r16_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r16_perc"])){ 
       $sql  .= $virgula." r16_perc = $this->r16_perc ";
       $virgula = ",";
       if(trim($this->r16_perc) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "r16_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r16_empres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r16_empres"])){ 
       $sql  .= $virgula." r16_empres = $this->r16_empres ";
       $virgula = ",";
       if(trim($this->r16_empres) == null ){ 
         $this->erro_sql = " Campo Empresa nao Informado.";
         $this->erro_campo = "r16_empres";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r16_anousu!=null){
       $sql .= " r16_anousu = $this->r16_anousu";
     }
     if($r16_mesusu!=null){
       $sql .= " and  r16_mesusu = $this->r16_mesusu";
     }
     if($r16_codigo!=null){
       $sql .= " and  r16_codigo = '$this->r16_codigo'";
     }
     if($r16_instit!=null){
       $sql .= " and  r16_instit = $this->r16_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r16_anousu,$this->r16_mesusu,$this->r16_codigo,$this->r16_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4532,'$this->r16_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4533,'$this->r16_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4534,'$this->r16_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,9912,'$this->r16_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r16_instit"]))
           $resac = db_query("insert into db_acount values($acount,600,9912,'".AddSlashes(pg_result($resaco,$conresaco,'r16_instit'))."','$this->r16_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r16_anousu"]))
           $resac = db_query("insert into db_acount values($acount,600,4532,'".AddSlashes(pg_result($resaco,$conresaco,'r16_anousu'))."','$this->r16_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r16_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,600,4533,'".AddSlashes(pg_result($resaco,$conresaco,'r16_mesusu'))."','$this->r16_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r16_codigo"]))
           $resac = db_query("insert into db_acount values($acount,600,4534,'".AddSlashes(pg_result($resaco,$conresaco,'r16_codigo'))."','$this->r16_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r16_descr"]))
           $resac = db_query("insert into db_acount values($acount,600,4535,'".AddSlashes(pg_result($resaco,$conresaco,'r16_descr'))."','$this->r16_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r16_valor"]))
           $resac = db_query("insert into db_acount values($acount,600,4536,'".AddSlashes(pg_result($resaco,$conresaco,'r16_valor'))."','$this->r16_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r16_perc"]))
           $resac = db_query("insert into db_acount values($acount,600,4537,'".AddSlashes(pg_result($resaco,$conresaco,'r16_perc'))."','$this->r16_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r16_empres"]))
           $resac = db_query("insert into db_acount values($acount,600,4538,'".AddSlashes(pg_result($resaco,$conresaco,'r16_empres'))."','$this->r16_empres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empresas para Vale Transportes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r16_anousu."-".$this->r16_mesusu."-".$this->r16_codigo."-".$this->r16_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empresas para Vale Transportes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r16_anousu."-".$this->r16_mesusu."-".$this->r16_codigo."-".$this->r16_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r16_anousu."-".$this->r16_mesusu."-".$this->r16_codigo."-".$this->r16_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r16_anousu=null,$r16_mesusu=null,$r16_codigo=null,$r16_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r16_anousu,$r16_mesusu,$r16_codigo,$r16_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4532,'$r16_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4533,'$r16_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4534,'$r16_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,9912,'$r16_instit','E')");
         $resac = db_query("insert into db_acount values($acount,600,9912,'','".AddSlashes(pg_result($resaco,$iresaco,'r16_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,600,4532,'','".AddSlashes(pg_result($resaco,$iresaco,'r16_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,600,4533,'','".AddSlashes(pg_result($resaco,$iresaco,'r16_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,600,4534,'','".AddSlashes(pg_result($resaco,$iresaco,'r16_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,600,4535,'','".AddSlashes(pg_result($resaco,$iresaco,'r16_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,600,4536,'','".AddSlashes(pg_result($resaco,$iresaco,'r16_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,600,4537,'','".AddSlashes(pg_result($resaco,$iresaco,'r16_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,600,4538,'','".AddSlashes(pg_result($resaco,$iresaco,'r16_empres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vtfempr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r16_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r16_anousu = $r16_anousu ";
        }
        if($r16_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r16_mesusu = $r16_mesusu ";
        }
        if($r16_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r16_codigo = '$r16_codigo' ";
        }
        if($r16_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r16_instit = $r16_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empresas para Vale Transportes nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r16_anousu."-".$r16_mesusu."-".$r16_codigo."-".$r16_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Empresas para Vale Transportes nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r16_anousu."-".$r16_mesusu."-".$r16_codigo."-".$r16_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r16_anousu."-".$r16_mesusu."-".$r16_codigo."-".$r16_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:vtfempr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r16_anousu=null,$r16_mesusu=null,$r16_codigo=null,$r16_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vtfempr ";
     $sql .= "      inner join db_config    on  db_config.codigo = vtfempr.r16_instit";
     $sql .= "      inner join rhempresavt  on  rhempresavt.rh35_codigo = vtfempr.r16_empres::INT
                                           and  rhempresavt.rh35_instit = vtfempr.r16_instit ";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r16_anousu!=null ){
         $sql2 .= " where vtfempr.r16_anousu = $r16_anousu "; 
       } 
       if($r16_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfempr.r16_mesusu = $r16_mesusu "; 
       } 
       if($r16_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfempr.r16_codigo = '$r16_codigo' "; 
       } 
       if($r16_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfempr.r16_instit = $r16_instit "; 
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
   function sql_query_file ( $r16_anousu=null,$r16_mesusu=null,$r16_codigo=null,$r16_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vtfempr ";
     $sql2 = "";
     if($dbwhere==""){
       if($r16_anousu!=null ){
         $sql2 .= " where vtfempr.r16_anousu = $r16_anousu "; 
       } 
       if($r16_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfempr.r16_mesusu = $r16_mesusu "; 
       } 
       if($r16_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfempr.r16_codigo = '$r16_codigo' "; 
       } 
       if($r16_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfempr.r16_instit = $r16_instit "; 
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