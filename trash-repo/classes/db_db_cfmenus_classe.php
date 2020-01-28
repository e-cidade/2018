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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_cfmenus
class cl_db_cfmenus { 
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
   var $verthoriz = null; 
   var $espmenuprinc = 0; 
   var $fonte = null; 
   var $fontetam = 0; 
   var $negrito = null; 
   var $italico = null; 
   var $largborda = 0; 
   var $corborda = null; 
   var $altmenuprinc = 0; 
   var $largmenuprinc = 0; 
   var $corfundomenuprinc = null; 
   var $corfontemenuprinc = null; 
   var $corfontemenuprincover = null; 
   var $altmenu = 0; 
   var $largmenu = 0; 
   var $corfundomenu = null; 
   var $corfundoover = null; 
   var $corfontemenu = null; 
   var $corfontemenuover = null; 
   var $posx = 0; 
   var $posy = 0; 
   var $id_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 verthoriz = varchar(1) = Vertical/Horizontal 
                 espmenuprinc = int4 = Espaço Menu Principal 
                 fonte = varchar(100) = Tipo de Fonte 
                 fontetam = int4 = Tamanho Fonte 
                 negrito = varchar(1) = Negrito 
                 italico = varchar(1) = Itálico 
                 largborda = int4 = Largura Borda 
                 corborda = varchar(10) = Cor da borda 
                 altmenuprinc = int4 = Altrua Menu Pincipal 
                 largmenuprinc = int4 = Largura Menu Principal 
                 corfundomenuprinc = varchar(10) = Cor Fundo Menu 
                 corfontemenuprinc = varchar(10) = Cor da Fonte Menu 
                 corfontemenuprincover = varchar(10) = Cor Fonte Over 
                 altmenu = int4 = Altura Menu 
                 largmenu = int4 = Largura do Menu 
                 corfundomenu = varchar(10) = Cor Fundo Menu 
                 corfundoover = varchar(10) = Cor Fundo Seleciona 
                 corfontemenu = varchar(10) = Cor Fonte Menu 
                 corfontemenuover = varchar(10) = Cor Fonte Menu Seleciona 
                 posx = int4 = Posição X 
                 posy = int4 = Posição Y 
                 id_usuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_db_cfmenus() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_cfmenus"); 
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
       $this->verthoriz = ($this->verthoriz == ""?@$GLOBALS["HTTP_POST_VARS"]["verthoriz"]:$this->verthoriz);
       $this->espmenuprinc = ($this->espmenuprinc == ""?@$GLOBALS["HTTP_POST_VARS"]["espmenuprinc"]:$this->espmenuprinc);
       $this->fonte = ($this->fonte == ""?@$GLOBALS["HTTP_POST_VARS"]["fonte"]:$this->fonte);
       $this->fontetam = ($this->fontetam == ""?@$GLOBALS["HTTP_POST_VARS"]["fontetam"]:$this->fontetam);
       $this->negrito = ($this->negrito == ""?@$GLOBALS["HTTP_POST_VARS"]["negrito"]:$this->negrito);
       $this->italico = ($this->italico == ""?@$GLOBALS["HTTP_POST_VARS"]["italico"]:$this->italico);
       $this->largborda = ($this->largborda == ""?@$GLOBALS["HTTP_POST_VARS"]["largborda"]:$this->largborda);
       $this->corborda = ($this->corborda == ""?@$GLOBALS["HTTP_POST_VARS"]["corborda"]:$this->corborda);
       $this->altmenuprinc = ($this->altmenuprinc == ""?@$GLOBALS["HTTP_POST_VARS"]["altmenuprinc"]:$this->altmenuprinc);
       $this->largmenuprinc = ($this->largmenuprinc == ""?@$GLOBALS["HTTP_POST_VARS"]["largmenuprinc"]:$this->largmenuprinc);
       $this->corfundomenuprinc = ($this->corfundomenuprinc == ""?@$GLOBALS["HTTP_POST_VARS"]["corfundomenuprinc"]:$this->corfundomenuprinc);
       $this->corfontemenuprinc = ($this->corfontemenuprinc == ""?@$GLOBALS["HTTP_POST_VARS"]["corfontemenuprinc"]:$this->corfontemenuprinc);
       $this->corfontemenuprincover = ($this->corfontemenuprincover == ""?@$GLOBALS["HTTP_POST_VARS"]["corfontemenuprincover"]:$this->corfontemenuprincover);
       $this->altmenu = ($this->altmenu == ""?@$GLOBALS["HTTP_POST_VARS"]["altmenu"]:$this->altmenu);
       $this->largmenu = ($this->largmenu == ""?@$GLOBALS["HTTP_POST_VARS"]["largmenu"]:$this->largmenu);
       $this->corfundomenu = ($this->corfundomenu == ""?@$GLOBALS["HTTP_POST_VARS"]["corfundomenu"]:$this->corfundomenu);
       $this->corfundoover = ($this->corfundoover == ""?@$GLOBALS["HTTP_POST_VARS"]["corfundoover"]:$this->corfundoover);
       $this->corfontemenu = ($this->corfontemenu == ""?@$GLOBALS["HTTP_POST_VARS"]["corfontemenu"]:$this->corfontemenu);
       $this->corfontemenuover = ($this->corfontemenuover == ""?@$GLOBALS["HTTP_POST_VARS"]["corfontemenuover"]:$this->corfontemenuover);
       $this->posx = ($this->posx == ""?@$GLOBALS["HTTP_POST_VARS"]["posx"]:$this->posx);
       $this->posy = ($this->posy == ""?@$GLOBALS["HTTP_POST_VARS"]["posy"]:$this->posy);
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
     }else{
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
     }
   }
   // funcao para inclusao
   function incluir ($id_usuario){ 
      $this->atualizacampos();
     if($this->verthoriz == null ){ 
       $this->erro_sql = " Campo Vertical/Horizontal nao Informado.";
       $this->erro_campo = "verthoriz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->espmenuprinc == null ){ 
       $this->erro_sql = " Campo Espaço Menu Principal nao Informado.";
       $this->erro_campo = "espmenuprinc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fonte == null ){ 
       $this->erro_sql = " Campo Tipo de Fonte nao Informado.";
       $this->erro_campo = "fonte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fontetam == null ){ 
       $this->erro_sql = " Campo Tamanho Fonte nao Informado.";
       $this->erro_campo = "fontetam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->negrito == null ){ 
       $this->erro_sql = " Campo Negrito nao Informado.";
       $this->erro_campo = "negrito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->italico == null ){ 
       $this->erro_sql = " Campo Itálico nao Informado.";
       $this->erro_campo = "italico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->largborda == null ){ 
       $this->erro_sql = " Campo Largura Borda nao Informado.";
       $this->erro_campo = "largborda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corborda == null ){ 
       $this->erro_sql = " Campo Cor da borda nao Informado.";
       $this->erro_campo = "corborda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->altmenuprinc == null ){ 
       $this->erro_sql = " Campo Altrua Menu Pincipal nao Informado.";
       $this->erro_campo = "altmenuprinc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->largmenuprinc == null ){ 
       $this->erro_sql = " Campo Largura Menu Principal nao Informado.";
       $this->erro_campo = "largmenuprinc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corfundomenuprinc == null ){ 
       $this->erro_sql = " Campo Cor Fundo Menu nao Informado.";
       $this->erro_campo = "corfundomenuprinc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corfontemenuprinc == null ){ 
       $this->erro_sql = " Campo Cor da Fonte Menu nao Informado.";
       $this->erro_campo = "corfontemenuprinc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corfontemenuprincover == null ){ 
       $this->erro_sql = " Campo Cor Fonte Over nao Informado.";
       $this->erro_campo = "corfontemenuprincover";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->altmenu == null ){ 
       $this->erro_sql = " Campo Altura Menu nao Informado.";
       $this->erro_campo = "altmenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->largmenu == null ){ 
       $this->erro_sql = " Campo Largura do Menu nao Informado.";
       $this->erro_campo = "largmenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corfundomenu == null ){ 
       $this->erro_sql = " Campo Cor Fundo Menu nao Informado.";
       $this->erro_campo = "corfundomenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corfundoover == null ){ 
       $this->erro_sql = " Campo Cor Fundo Seleciona nao Informado.";
       $this->erro_campo = "corfundoover";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corfontemenu == null ){ 
       $this->erro_sql = " Campo Cor Fonte Menu nao Informado.";
       $this->erro_campo = "corfontemenu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->corfontemenuover == null ){ 
       $this->erro_sql = " Campo Cor Fonte Menu Seleciona nao Informado.";
       $this->erro_campo = "corfontemenuover";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->posx == null ){ 
       $this->erro_sql = " Campo Posição X nao Informado.";
       $this->erro_campo = "posx";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->posy == null ){ 
       $this->erro_sql = " Campo Posição Y nao Informado.";
       $this->erro_campo = "posy";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->id_usuario = $id_usuario; 
     if(($this->id_usuario == null) || ($this->id_usuario == "") ){ 
       $this->erro_sql = " Campo id_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_cfmenus(
                                       verthoriz 
                                      ,espmenuprinc 
                                      ,fonte 
                                      ,fontetam 
                                      ,negrito 
                                      ,italico 
                                      ,largborda 
                                      ,corborda 
                                      ,altmenuprinc 
                                      ,largmenuprinc 
                                      ,corfundomenuprinc 
                                      ,corfontemenuprinc 
                                      ,corfontemenuprincover 
                                      ,altmenu 
                                      ,largmenu 
                                      ,corfundomenu 
                                      ,corfundoover 
                                      ,corfontemenu 
                                      ,corfontemenuover 
                                      ,posx 
                                      ,posy 
                                      ,id_usuario 
                       )
                values (
                                '$this->verthoriz' 
                               ,$this->espmenuprinc 
                               ,'$this->fonte' 
                               ,$this->fontetam 
                               ,'$this->negrito' 
                               ,'$this->italico' 
                               ,$this->largborda 
                               ,'$this->corborda' 
                               ,$this->altmenuprinc 
                               ,$this->largmenuprinc 
                               ,'$this->corfundomenuprinc' 
                               ,'$this->corfontemenuprinc' 
                               ,'$this->corfontemenuprincover' 
                               ,$this->altmenu 
                               ,$this->largmenu 
                               ,'$this->corfundomenu' 
                               ,'$this->corfundoover' 
                               ,'$this->corfontemenu' 
                               ,'$this->corfontemenuover' 
                               ,$this->posx 
                               ,$this->posy 
                               ,$this->id_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuracao Menus ($this->id_usuario) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuracao Menus já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuracao Menus ($this->id_usuario) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->id_usuario));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,568,'$this->id_usuario','I')");
       $resac = db_query("insert into db_acount values($acount,152,785,'','".AddSlashes(pg_result($resaco,0,'verthoriz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,786,'','".AddSlashes(pg_result($resaco,0,'espmenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,787,'','".AddSlashes(pg_result($resaco,0,'fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,788,'','".AddSlashes(pg_result($resaco,0,'fontetam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,789,'','".AddSlashes(pg_result($resaco,0,'negrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,790,'','".AddSlashes(pg_result($resaco,0,'italico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,791,'','".AddSlashes(pg_result($resaco,0,'largborda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,792,'','".AddSlashes(pg_result($resaco,0,'corborda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,793,'','".AddSlashes(pg_result($resaco,0,'altmenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,794,'','".AddSlashes(pg_result($resaco,0,'largmenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,795,'','".AddSlashes(pg_result($resaco,0,'corfundomenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,796,'','".AddSlashes(pg_result($resaco,0,'corfontemenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,797,'','".AddSlashes(pg_result($resaco,0,'corfontemenuprincover'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,798,'','".AddSlashes(pg_result($resaco,0,'altmenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,799,'','".AddSlashes(pg_result($resaco,0,'largmenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,800,'','".AddSlashes(pg_result($resaco,0,'corfundomenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,801,'','".AddSlashes(pg_result($resaco,0,'corfundoover'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,802,'','".AddSlashes(pg_result($resaco,0,'corfontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,803,'','".AddSlashes(pg_result($resaco,0,'corfontemenuover'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,804,'','".AddSlashes(pg_result($resaco,0,'posx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,805,'','".AddSlashes(pg_result($resaco,0,'posy'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,152,568,'','".AddSlashes(pg_result($resaco,0,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($id_usuario=null) { 
      $this->atualizacampos();
     $sql = " update db_cfmenus set ";
     $virgula = "";
     if(trim($this->verthoriz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["verthoriz"])){ 
       $sql  .= $virgula." verthoriz = '$this->verthoriz' ";
       $virgula = ",";
       if(trim($this->verthoriz) == null ){ 
         $this->erro_sql = " Campo Vertical/Horizontal nao Informado.";
         $this->erro_campo = "verthoriz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->espmenuprinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["espmenuprinc"])){ 
       $sql  .= $virgula." espmenuprinc = $this->espmenuprinc ";
       $virgula = ",";
       if(trim($this->espmenuprinc) == null ){ 
         $this->erro_sql = " Campo Espaço Menu Principal nao Informado.";
         $this->erro_campo = "espmenuprinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fonte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fonte"])){ 
       $sql  .= $virgula." fonte = '$this->fonte' ";
       $virgula = ",";
       if(trim($this->fonte) == null ){ 
         $this->erro_sql = " Campo Tipo de Fonte nao Informado.";
         $this->erro_campo = "fonte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fontetam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fontetam"])){ 
       $sql  .= $virgula." fontetam = $this->fontetam ";
       $virgula = ",";
       if(trim($this->fontetam) == null ){ 
         $this->erro_sql = " Campo Tamanho Fonte nao Informado.";
         $this->erro_campo = "fontetam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->negrito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["negrito"])){ 
       $sql  .= $virgula." negrito = '$this->negrito' ";
       $virgula = ",";
       if(trim($this->negrito) == null ){ 
         $this->erro_sql = " Campo Negrito nao Informado.";
         $this->erro_campo = "negrito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->italico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["italico"])){ 
       $sql  .= $virgula." italico = '$this->italico' ";
       $virgula = ",";
       if(trim($this->italico) == null ){ 
         $this->erro_sql = " Campo Itálico nao Informado.";
         $this->erro_campo = "italico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->largborda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["largborda"])){ 
       $sql  .= $virgula." largborda = $this->largborda ";
       $virgula = ",";
       if(trim($this->largborda) == null ){ 
         $this->erro_sql = " Campo Largura Borda nao Informado.";
         $this->erro_campo = "largborda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corborda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corborda"])){ 
       $sql  .= $virgula." corborda = '$this->corborda' ";
       $virgula = ",";
       if(trim($this->corborda) == null ){ 
         $this->erro_sql = " Campo Cor da borda nao Informado.";
         $this->erro_campo = "corborda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->altmenuprinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["altmenuprinc"])){ 
       $sql  .= $virgula." altmenuprinc = $this->altmenuprinc ";
       $virgula = ",";
       if(trim($this->altmenuprinc) == null ){ 
         $this->erro_sql = " Campo Altrua Menu Pincipal nao Informado.";
         $this->erro_campo = "altmenuprinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->largmenuprinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["largmenuprinc"])){ 
       $sql  .= $virgula." largmenuprinc = $this->largmenuprinc ";
       $virgula = ",";
       if(trim($this->largmenuprinc) == null ){ 
         $this->erro_sql = " Campo Largura Menu Principal nao Informado.";
         $this->erro_campo = "largmenuprinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corfundomenuprinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corfundomenuprinc"])){ 
       $sql  .= $virgula." corfundomenuprinc = '$this->corfundomenuprinc' ";
       $virgula = ",";
       if(trim($this->corfundomenuprinc) == null ){ 
         $this->erro_sql = " Campo Cor Fundo Menu nao Informado.";
         $this->erro_campo = "corfundomenuprinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corfontemenuprinc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corfontemenuprinc"])){ 
       $sql  .= $virgula." corfontemenuprinc = '$this->corfontemenuprinc' ";
       $virgula = ",";
       if(trim($this->corfontemenuprinc) == null ){ 
         $this->erro_sql = " Campo Cor da Fonte Menu nao Informado.";
         $this->erro_campo = "corfontemenuprinc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corfontemenuprincover)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corfontemenuprincover"])){ 
       $sql  .= $virgula." corfontemenuprincover = '$this->corfontemenuprincover' ";
       $virgula = ",";
       if(trim($this->corfontemenuprincover) == null ){ 
         $this->erro_sql = " Campo Cor Fonte Over nao Informado.";
         $this->erro_campo = "corfontemenuprincover";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->altmenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["altmenu"])){ 
       $sql  .= $virgula." altmenu = $this->altmenu ";
       $virgula = ",";
       if(trim($this->altmenu) == null ){ 
         $this->erro_sql = " Campo Altura Menu nao Informado.";
         $this->erro_campo = "altmenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->largmenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["largmenu"])){ 
       $sql  .= $virgula." largmenu = $this->largmenu ";
       $virgula = ",";
       if(trim($this->largmenu) == null ){ 
         $this->erro_sql = " Campo Largura do Menu nao Informado.";
         $this->erro_campo = "largmenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corfundomenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corfundomenu"])){ 
       $sql  .= $virgula." corfundomenu = '$this->corfundomenu' ";
       $virgula = ",";
       if(trim($this->corfundomenu) == null ){ 
         $this->erro_sql = " Campo Cor Fundo Menu nao Informado.";
         $this->erro_campo = "corfundomenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corfundoover)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corfundoover"])){ 
       $sql  .= $virgula." corfundoover = '$this->corfundoover' ";
       $virgula = ",";
       if(trim($this->corfundoover) == null ){ 
         $this->erro_sql = " Campo Cor Fundo Seleciona nao Informado.";
         $this->erro_campo = "corfundoover";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corfontemenu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corfontemenu"])){ 
       $sql  .= $virgula." corfontemenu = '$this->corfontemenu' ";
       $virgula = ",";
       if(trim($this->corfontemenu) == null ){ 
         $this->erro_sql = " Campo Cor Fonte Menu nao Informado.";
         $this->erro_campo = "corfontemenu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->corfontemenuover)!="" || isset($GLOBALS["HTTP_POST_VARS"]["corfontemenuover"])){ 
       $sql  .= $virgula." corfontemenuover = '$this->corfontemenuover' ";
       $virgula = ",";
       if(trim($this->corfontemenuover) == null ){ 
         $this->erro_sql = " Campo Cor Fonte Menu Seleciona nao Informado.";
         $this->erro_campo = "corfontemenuover";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->posx)!="" || isset($GLOBALS["HTTP_POST_VARS"]["posx"])){ 
       $sql  .= $virgula." posx = $this->posx ";
       $virgula = ",";
       if(trim($this->posx) == null ){ 
         $this->erro_sql = " Campo Posição X nao Informado.";
         $this->erro_campo = "posx";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->posy)!="" || isset($GLOBALS["HTTP_POST_VARS"]["posy"])){ 
       $sql  .= $virgula." posy = $this->posy ";
       $virgula = ",";
       if(trim($this->posy) == null ){ 
         $this->erro_sql = " Campo Posição Y nao Informado.";
         $this->erro_campo = "posy";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){ 
       $sql  .= $virgula." id_usuario = $this->id_usuario ";
       $virgula = ",";
       if(trim($this->id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($id_usuario!=null){
       $sql .= " id_usuario = $this->id_usuario";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->id_usuario));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,568,'$this->id_usuario','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["verthoriz"]))
           $resac = db_query("insert into db_acount values($acount,152,785,'".AddSlashes(pg_result($resaco,$conresaco,'verthoriz'))."','$this->verthoriz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["espmenuprinc"]))
           $resac = db_query("insert into db_acount values($acount,152,786,'".AddSlashes(pg_result($resaco,$conresaco,'espmenuprinc'))."','$this->espmenuprinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fonte"]))
           $resac = db_query("insert into db_acount values($acount,152,787,'".AddSlashes(pg_result($resaco,$conresaco,'fonte'))."','$this->fonte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fontetam"]))
           $resac = db_query("insert into db_acount values($acount,152,788,'".AddSlashes(pg_result($resaco,$conresaco,'fontetam'))."','$this->fontetam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["negrito"]))
           $resac = db_query("insert into db_acount values($acount,152,789,'".AddSlashes(pg_result($resaco,$conresaco,'negrito'))."','$this->negrito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["italico"]))
           $resac = db_query("insert into db_acount values($acount,152,790,'".AddSlashes(pg_result($resaco,$conresaco,'italico'))."','$this->italico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["largborda"]))
           $resac = db_query("insert into db_acount values($acount,152,791,'".AddSlashes(pg_result($resaco,$conresaco,'largborda'))."','$this->largborda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corborda"]))
           $resac = db_query("insert into db_acount values($acount,152,792,'".AddSlashes(pg_result($resaco,$conresaco,'corborda'))."','$this->corborda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["altmenuprinc"]))
           $resac = db_query("insert into db_acount values($acount,152,793,'".AddSlashes(pg_result($resaco,$conresaco,'altmenuprinc'))."','$this->altmenuprinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["largmenuprinc"]))
           $resac = db_query("insert into db_acount values($acount,152,794,'".AddSlashes(pg_result($resaco,$conresaco,'largmenuprinc'))."','$this->largmenuprinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corfundomenuprinc"]))
           $resac = db_query("insert into db_acount values($acount,152,795,'".AddSlashes(pg_result($resaco,$conresaco,'corfundomenuprinc'))."','$this->corfundomenuprinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corfontemenuprinc"]))
           $resac = db_query("insert into db_acount values($acount,152,796,'".AddSlashes(pg_result($resaco,$conresaco,'corfontemenuprinc'))."','$this->corfontemenuprinc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corfontemenuprincover"]))
           $resac = db_query("insert into db_acount values($acount,152,797,'".AddSlashes(pg_result($resaco,$conresaco,'corfontemenuprincover'))."','$this->corfontemenuprincover',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["altmenu"]))
           $resac = db_query("insert into db_acount values($acount,152,798,'".AddSlashes(pg_result($resaco,$conresaco,'altmenu'))."','$this->altmenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["largmenu"]))
           $resac = db_query("insert into db_acount values($acount,152,799,'".AddSlashes(pg_result($resaco,$conresaco,'largmenu'))."','$this->largmenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corfundomenu"]))
           $resac = db_query("insert into db_acount values($acount,152,800,'".AddSlashes(pg_result($resaco,$conresaco,'corfundomenu'))."','$this->corfundomenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corfundoover"]))
           $resac = db_query("insert into db_acount values($acount,152,801,'".AddSlashes(pg_result($resaco,$conresaco,'corfundoover'))."','$this->corfundoover',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corfontemenu"]))
           $resac = db_query("insert into db_acount values($acount,152,802,'".AddSlashes(pg_result($resaco,$conresaco,'corfontemenu'))."','$this->corfontemenu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["corfontemenuover"]))
           $resac = db_query("insert into db_acount values($acount,152,803,'".AddSlashes(pg_result($resaco,$conresaco,'corfontemenuover'))."','$this->corfontemenuover',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["posx"]))
           $resac = db_query("insert into db_acount values($acount,152,804,'".AddSlashes(pg_result($resaco,$conresaco,'posx'))."','$this->posx',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["posy"]))
           $resac = db_query("insert into db_acount values($acount,152,805,'".AddSlashes(pg_result($resaco,$conresaco,'posy'))."','$this->posy',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,152,568,'".AddSlashes(pg_result($resaco,$conresaco,'id_usuario'))."','$this->id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao Menus nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao Menus nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($id_usuario=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($id_usuario));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,568,'$id_usuario','E')");
         $resac = db_query("insert into db_acount values($acount,152,785,'','".AddSlashes(pg_result($resaco,$iresaco,'verthoriz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,786,'','".AddSlashes(pg_result($resaco,$iresaco,'espmenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,787,'','".AddSlashes(pg_result($resaco,$iresaco,'fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,788,'','".AddSlashes(pg_result($resaco,$iresaco,'fontetam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,789,'','".AddSlashes(pg_result($resaco,$iresaco,'negrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,790,'','".AddSlashes(pg_result($resaco,$iresaco,'italico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,791,'','".AddSlashes(pg_result($resaco,$iresaco,'largborda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,792,'','".AddSlashes(pg_result($resaco,$iresaco,'corborda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,793,'','".AddSlashes(pg_result($resaco,$iresaco,'altmenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,794,'','".AddSlashes(pg_result($resaco,$iresaco,'largmenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,795,'','".AddSlashes(pg_result($resaco,$iresaco,'corfundomenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,796,'','".AddSlashes(pg_result($resaco,$iresaco,'corfontemenuprinc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,797,'','".AddSlashes(pg_result($resaco,$iresaco,'corfontemenuprincover'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,798,'','".AddSlashes(pg_result($resaco,$iresaco,'altmenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,799,'','".AddSlashes(pg_result($resaco,$iresaco,'largmenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,800,'','".AddSlashes(pg_result($resaco,$iresaco,'corfundomenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,801,'','".AddSlashes(pg_result($resaco,$iresaco,'corfundoover'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,802,'','".AddSlashes(pg_result($resaco,$iresaco,'corfontemenu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,803,'','".AddSlashes(pg_result($resaco,$iresaco,'corfontemenuover'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,804,'','".AddSlashes(pg_result($resaco,$iresaco,'posx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,805,'','".AddSlashes(pg_result($resaco,$iresaco,'posy'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,152,568,'','".AddSlashes(pg_result($resaco,$iresaco,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_cfmenus
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_usuario = $id_usuario ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuracao Menus nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_usuario;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Configuracao Menus nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_usuario;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_usuario;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_cfmenus";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>