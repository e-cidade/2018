<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_cotasagendamento
class cl_sau_cotasagendamento { 
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
   var $s163_i_codigo = 0; 
   var $s163_i_rhcbo = 0; 
   var $s163_i_upssolicitante = 0; 
   var $s163_i_upsprestadora = 0; 
   var $s163_i_quantidade = 0; 
   var $s163_i_mescomp = 0; 
   var $s163_i_anocomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s163_i_codigo = int4 = Código 
                 s163_i_rhcbo = int4 = Especialidade 
                 s163_i_upssolicitante = int4 = Solicitante 
                 s163_i_upsprestadora = int4 = Prestadora 
                 s163_i_quantidade = int4 = Quantidade 
                 s163_i_mescomp = int4 = Mês 
                 s163_i_anocomp = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_sau_cotasagendamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_cotasagendamento"); 
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
       $this->s163_i_codigo = ($this->s163_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s163_i_codigo"]:$this->s163_i_codigo);
       $this->s163_i_rhcbo = ($this->s163_i_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["s163_i_rhcbo"]:$this->s163_i_rhcbo);
       $this->s163_i_upssolicitante = ($this->s163_i_upssolicitante == ""?@$GLOBALS["HTTP_POST_VARS"]["s163_i_upssolicitante"]:$this->s163_i_upssolicitante);
       $this->s163_i_upsprestadora = ($this->s163_i_upsprestadora == ""?@$GLOBALS["HTTP_POST_VARS"]["s163_i_upsprestadora"]:$this->s163_i_upsprestadora);
       $this->s163_i_quantidade = ($this->s163_i_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["s163_i_quantidade"]:$this->s163_i_quantidade);
       $this->s163_i_mescomp = ($this->s163_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["s163_i_mescomp"]:$this->s163_i_mescomp);
       $this->s163_i_anocomp = ($this->s163_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["s163_i_anocomp"]:$this->s163_i_anocomp);
     }else{
       $this->s163_i_codigo = ($this->s163_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s163_i_codigo"]:$this->s163_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($s163_i_codigo){ 
      $this->atualizacampos();
     if($this->s163_i_rhcbo == null ){ 
       $this->erro_sql = " Campo Especialidade nao Informado.";
       $this->erro_campo = "s163_i_rhcbo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s163_i_upssolicitante == null ){ 
       $this->erro_sql = " Campo Solicitante nao Informado.";
       $this->erro_campo = "s163_i_upssolicitante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s163_i_upsprestadora == null ){ 
       $this->erro_sql = " Campo Prestadora nao Informado.";
       $this->erro_campo = "s163_i_upsprestadora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s163_i_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "s163_i_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s163_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "s163_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s163_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "s163_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s163_i_codigo == "" || $s163_i_codigo == null ){
       $result = db_query("select nextval('sau_cotasagendamento_s163_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_cotasagendamento_s163_i_codigo_seq do campo: s163_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s163_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_cotasagendamento_s163_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s163_i_codigo)){
         $this->erro_sql = " Campo s163_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s163_i_codigo = $s163_i_codigo; 
       }
     }
     if(($this->s163_i_codigo == null) || ($this->s163_i_codigo == "") ){ 
       $this->erro_sql = " Campo s163_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_cotasagendamento(
                                       s163_i_codigo 
                                      ,s163_i_rhcbo 
                                      ,s163_i_upssolicitante 
                                      ,s163_i_upsprestadora 
                                      ,s163_i_quantidade 
                                      ,s163_i_mescomp 
                                      ,s163_i_anocomp 
                       )
                values (
                                $this->s163_i_codigo 
                               ,$this->s163_i_rhcbo 
                               ,$this->s163_i_upssolicitante 
                               ,$this->s163_i_upsprestadora 
                               ,$this->s163_i_quantidade 
                               ,$this->s163_i_mescomp 
                               ,$this->s163_i_anocomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cotas de agendamento ($this->s163_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cotas de agendamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cotas de agendamento ($this->s163_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s163_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->s163_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18013,'$this->s163_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3184,18013,'','".AddSlashes(pg_result($resaco,0,'s163_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3184,18014,'','".AddSlashes(pg_result($resaco,0,'s163_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3184,18019,'','".AddSlashes(pg_result($resaco,0,'s163_i_upssolicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3184,18015,'','".AddSlashes(pg_result($resaco,0,'s163_i_upsprestadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3184,18016,'','".AddSlashes(pg_result($resaco,0,'s163_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3184,18017,'','".AddSlashes(pg_result($resaco,0,'s163_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3184,18018,'','".AddSlashes(pg_result($resaco,0,'s163_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($s163_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_cotasagendamento set ";
     $virgula = "";
     if(trim($this->s163_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s163_i_codigo"])){ 
       $sql  .= $virgula." s163_i_codigo = $this->s163_i_codigo ";
       $virgula = ",";
       if(trim($this->s163_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "s163_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s163_i_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s163_i_rhcbo"])){ 
       $sql  .= $virgula." s163_i_rhcbo = $this->s163_i_rhcbo ";
       $virgula = ",";
       if(trim($this->s163_i_rhcbo) == null ){ 
         $this->erro_sql = " Campo Especialidade nao Informado.";
         $this->erro_campo = "s163_i_rhcbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s163_i_upssolicitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s163_i_upssolicitante"])){ 
       $sql  .= $virgula." s163_i_upssolicitante = $this->s163_i_upssolicitante ";
       $virgula = ",";
       if(trim($this->s163_i_upssolicitante) == null ){ 
         $this->erro_sql = " Campo Solicitante nao Informado.";
         $this->erro_campo = "s163_i_upssolicitante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s163_i_upsprestadora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s163_i_upsprestadora"])){ 
       $sql  .= $virgula." s163_i_upsprestadora = $this->s163_i_upsprestadora ";
       $virgula = ",";
       if(trim($this->s163_i_upsprestadora) == null ){ 
         $this->erro_sql = " Campo Prestadora nao Informado.";
         $this->erro_campo = "s163_i_upsprestadora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s163_i_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s163_i_quantidade"])){ 
       $sql  .= $virgula." s163_i_quantidade = $this->s163_i_quantidade ";
       $virgula = ",";
       if(trim($this->s163_i_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "s163_i_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s163_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s163_i_mescomp"])){ 
       $sql  .= $virgula." s163_i_mescomp = $this->s163_i_mescomp ";
       $virgula = ",";
       if(trim($this->s163_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "s163_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s163_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s163_i_anocomp"])){ 
       $sql  .= $virgula." s163_i_anocomp = $this->s163_i_anocomp ";
       $virgula = ",";
       if(trim($this->s163_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "s163_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s163_i_codigo!=null){
       $sql .= " s163_i_codigo = $this->s163_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->s163_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18013,'$this->s163_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s163_i_codigo"]) || $this->s163_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3184,18013,'".AddSlashes(pg_result($resaco,$conresaco,'s163_i_codigo'))."','$this->s163_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s163_i_rhcbo"]) || $this->s163_i_rhcbo != "")
           $resac = db_query("insert into db_acount values($acount,3184,18014,'".AddSlashes(pg_result($resaco,$conresaco,'s163_i_rhcbo'))."','$this->s163_i_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s163_i_upssolicitante"]) || $this->s163_i_upssolicitante != "")
           $resac = db_query("insert into db_acount values($acount,3184,18019,'".AddSlashes(pg_result($resaco,$conresaco,'s163_i_upssolicitante'))."','$this->s163_i_upssolicitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s163_i_upsprestadora"]) || $this->s163_i_upsprestadora != "")
           $resac = db_query("insert into db_acount values($acount,3184,18015,'".AddSlashes(pg_result($resaco,$conresaco,'s163_i_upsprestadora'))."','$this->s163_i_upsprestadora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s163_i_quantidade"]) || $this->s163_i_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,3184,18016,'".AddSlashes(pg_result($resaco,$conresaco,'s163_i_quantidade'))."','$this->s163_i_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s163_i_mescomp"]) || $this->s163_i_mescomp != "")
           $resac = db_query("insert into db_acount values($acount,3184,18017,'".AddSlashes(pg_result($resaco,$conresaco,'s163_i_mescomp'))."','$this->s163_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["s163_i_anocomp"]) || $this->s163_i_anocomp != "")
           $resac = db_query("insert into db_acount values($acount,3184,18018,'".AddSlashes(pg_result($resaco,$conresaco,'s163_i_anocomp'))."','$this->s163_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cotas de agendamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s163_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cotas de agendamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s163_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s163_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($s163_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($s163_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18013,'$s163_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3184,18013,'','".AddSlashes(pg_result($resaco,$iresaco,'s163_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3184,18014,'','".AddSlashes(pg_result($resaco,$iresaco,'s163_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3184,18019,'','".AddSlashes(pg_result($resaco,$iresaco,'s163_i_upssolicitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3184,18015,'','".AddSlashes(pg_result($resaco,$iresaco,'s163_i_upsprestadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3184,18016,'','".AddSlashes(pg_result($resaco,$iresaco,'s163_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3184,18017,'','".AddSlashes(pg_result($resaco,$iresaco,'s163_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3184,18018,'','".AddSlashes(pg_result($resaco,$iresaco,'s163_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_cotasagendamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($s163_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " s163_i_codigo = $s163_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cotas de agendamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s163_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cotas de agendamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s163_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s163_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_cotasagendamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $s163_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_cotasagendamento ";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = sau_cotasagendamento.s163_i_rhcbo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = sau_cotasagendamento.s163_i_upsprestadora and  unidades.sd02_i_codigo = sau_cotasagendamento.s163_i_upssolicitante";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = unidades.sd02_i_diretor and  cgm.z01_numcgm = unidades.sd02_i_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      left  join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left  join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left  join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left  join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left  join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left  join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left  join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "      left  join sau_distritosanitario  on  sau_distritosanitario.s153_i_codigo = unidades.sd02_i_distrito";
     $sql2 = "";
     if($dbwhere==""){
       if($s163_i_codigo!=null ){
         $sql2 .= " where sau_cotasagendamento.s163_i_codigo = $s163_i_codigo "; 
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
   function sql_query_file ( $s163_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_cotasagendamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($s163_i_codigo!=null ){
         $sql2 .= " where sau_cotasagendamento.s163_i_codigo = $s163_i_codigo "; 
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
  
  function sql_query_cotas($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= ' from sau_cotasagendamento ';
    $sSql .= '  left  join sau_cotasagendamentoprofissional on s164_cotaagendamento = s163_i_codigo ';
    $sSql .= '  left  join especmedico on sd27_i_codigo = s164_especmedico ';
    $sSql .= '  left  join unidademedicos on sd04_i_codigo = sd27_i_undmed ';
    $sSql .= '  left  join medicos on sd03_i_codigo = sd04_i_medico ';
    $sSql .= '  left  join cgm on z01_numcgm = sd03_i_cgm';
    $sSql .= "  inner join rhcbo  on  rhcbo.rh70_sequencial = sau_cotasagendamento.s163_i_rhcbo";
    $sSql .= "  inner join unidades  on  unidades.sd02_i_codigo = sau_cotasagendamento.s163_i_upsprestadora";
    $sSql .= "  inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
    $sSql .= "  inner join unidades as upssolic on upssolic.sd02_i_codigo = sau_cotasagendamento.s163_i_upssolicitante";
    $sSql .= "  inner join db_depart as db_departsolic  on  db_departsolic.coddepto = upssolic.sd02_i_codigo";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where sau_cotasagendamento.sau163_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

}
?>